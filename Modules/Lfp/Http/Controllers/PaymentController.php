<?php

namespace Modules\Lfp\Http\Controllers;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Modules\Lfp\Entities\Lfp;
use Modules\Lfp\Entities\Payment;
use Modules\Lfp\Http\Requests\PaymentStoreRequest;
use Modules\Lfp\Http\Requests\PaymentEditRequest;

class PaymentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param bool $status
     * @param int $newApp
     *
     * @return \Inertia\Response
     */
    public function index($status = true, $newApp = 0): \Inertia\Response {
        $payments = $this->paginatePayments();

        return Inertia::render('Lfp::Payments', ['page' => 'applications', 'status' => $status, 'results' => $payments, 'app' => $newApp]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PaymentStoreRequest $request
     *
     * @return RedirectResponse
     */
    public function store(PaymentStoreRequest $request): RedirectResponse
    {
        $payment = Payment::create($request->validated());

        return Redirect::route('lfp.applications.show', [$payment->lfp_id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PaymentEditRequest $request
     * @param Payment $payment
     *
     * @return RedirectResponse
     */
    public function update(PaymentEditRequest $request, Payment $payment): RedirectResponse
    {
        Payment::where('id', $payment->id)->update($request->validated());
        $payment = Payment::find($payment->id);

        return Redirect::route('lfp.applications.show', [$payment->lfp_id]);
    }

    /**
     * @param Request $request
     * @param string $type
     * @param int $range
     *
     * @return \Illuminate\Http\Response|null
     */
    public function downloadPayments(Request $request, string $type, int $range): \Illuminate\Http\Response|null
    {
        // Disable the Debugbar for this specific response
        Debugbar::disable();

        $currentMonth = Carbon::now()->format('Y-m') . "-01";
        //$lastMonth = Carbon::now()->subMonth()->format('Y-m') . "-01";
        $monthBeforeLast = Carbon::now()->subMonths((integer)$range)->format('Y-m') . "-01";

        $payments = Payment::whereBetween('anniversary_date', [$monthBeforeLast, $currentMonth])
            ->orderByDesc('anniversary_date')
            ->with('lfp');

        if ($type != 'all') {
            if ($type == 'empty') {
                $payments = $payments->whereNull('oc_pay_status');
            } else {
                $payments = $payments->where('oc_pay_status', 'ilike', $type);
            }
        }

        try {

            $neededColumns = ['id', 'lfpApp_full_name_alias', 'lfpApp_first_name', 'lfpApp_last_name',
                'anniversary_date', 'proposed_pay_date', 'proposed_pay_amount',
                'proposed_hrs_of_service',	'sfas_pay_status',	'oc_pay_status',
                'lfpApp_sin', 'lfpApp_profession', 'lfpApp_employer', 'lfpApp_employment_status', 'lfpApp_community',
                'lfpApp_declined_removed_reason', ];

            //default to exporting awarded
            $payments = $payments->get();

            // Get column names for Payment and Product tables
            $paymentColumns = Schema::connection(env('DB_DATABASE_LFP'))->getColumnListing('payments');
            $lfpColumns = Schema::connection(env('DB_DATABASE_LFP'))->getColumnListing('lfps');

            // Prefix lfp columns to avoid name collisions
            $lfpColumns = array_map(fn($col) => 'lfpApp_' . $col, $lfpColumns);

            // Combine both payment and prefixed lfp columns for the header
            $headers = array_merge($paymentColumns, $lfpColumns);

            // Open a new CSV file for writing
            $filename = $type.'-'.$monthBeforeLast.'TO'.$currentMonth.'.csv';
            $csvFile = fopen('php://output', 'w');
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="'.$filename.'"');

            $neededHeader = [];
            foreach ($headers as $header) {
                if( in_array($header, $neededColumns)) {
                    $neededHeader[] = $header;
                }
            }
            // Write the headers to the CSV file
            fputcsv($csvFile, $neededHeader);

            // Loop through each payment and write the rows
            foreach ($payments as $payment) {
                // Get payment data
                $paymentData = $payment->getAttributes();

                // Get related product data and prefix the keys
                $lfpData = $payment->lfp ? $payment->lfp->getAttributes() : array_fill(0, count($lfpColumns), null);
                $prefixedLfpData = [];

                if ($payment->lfp) {
                    foreach ($lfpData as $key => $value) {
                        $prefixedLfpData['lfpApp_' . $key] = $value;
                    }
                } else {
                    $prefixedLfpData = array_fill_keys($lfpColumns, null); // Fill with nulls if no related lfp
                }

                // Combine payment and prefixed lfp data into a single row
                $row = array_merge($paymentData, $prefixedLfpData);

                $neededValues = [];
                foreach ($row as $key => $value) {
                    if( in_array($key, $neededColumns)) {
                        $neededValues[] = $value;
                    }
                }
                // Write the row to the CSV file
                fputcsv($csvFile, $neededValues);
            }


            // Close the CSV file
            fclose($csvFile);
            return new \Illuminate\Http\Response();

        } catch (Exception $exception) {
            Log::error('Error generating CSV for download: '.$exception);

            return Response::make('Internal server error.', 500, []);
        }
    }

    /**
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function paginatePayments(): mixed {
        $currentMonth = Carbon::now()->format('Y-m') . "-01";
        //$lastMonth = Carbon::now()->subMonth()->format('Y-m') . "-01";
        $monthBeforeLast = Carbon::now()->subMonths(2)->format('Y-m') . "-01";

        // If it is a filtered list then show records from today and back 6 months
        if (request()->has('filter_status') && request()->filter_status != 'all') {
            $monthBeforeLast = Carbon::now()->subMonths(6)->format('Y-m') . "-01";
        }

        $payments = Payment::whereBetween('anniversary_date', [$monthBeforeLast, $currentMonth])
            ->orderByDesc('anniversary_date')
            ->with('lfp');

        if (request()->has('filter_status') && request()->filter_status != 'all') {
            if (request()->filter_status == 'empty') {
                $payments = $payments->whereNull('oc_pay_status');
            } else {
                $payments = $payments->where('oc_pay_status', 'ilike', request()->filter_status);
            }
        }

        // Respect the sort and direction from the request
        $sort = request()->get('sort', 'anniversary_date');
        $direction = request()->get('direction', 'desc');

        $payments = $payments->orderBy($sort, $direction);

        $payments = $payments->paginate(25)->onEachSide(1)->appends(request()->query());

        // Inject payment data from sfas
        $payIds = $payments->pluck('pay_idx');

        // Fetch sfas payments data in a single batch if payIds are not empty
        $sfasPays = [];
        if ($payIds->isNotEmpty()) {
            $rawSfasPays = (new Payment)->sfasPayment($payIds->toArray());
            // Convert list to a map for quick lookup
            $sfasPays = collect($rawSfasPays)->keyBy('pl_loan_forgiveness_pay_idx');
        }

        // Inject sfas payment data into payments
        foreach ($payments as $payment) {
            $payment->sfas_payment = $sfasPays[$payment->pay_idx] ?? null;
        }

        // Fetch lfps in a single batch query
        $appIds = $payments->pluck('app_idx')->unique();
        $lfps = Lfp::whereIn('app_idx', $appIds)->get()->keyBy('app_idx');

        // Fetch individual data from sfas in a single batch if applicable
        $sins = $lfps->pluck('sin')->unique()->toArray();
        $sfasInds = !empty($sins) ? (new Lfp)->sfasInd($sins) : [];
        $sfasIndMap = collect($sfasInds)->keyBy('sin');

        // Append to lfps with sfas individual data
        foreach ($lfps as $lfp) {
            $lfp->sfas_ind = $sfasIndMap[$lfp->sin] ?? null;
        }

        // Inject sfas individual data into payments
        foreach ($payments as $payment) {
            $payment->sfas_ind = $lfps[$payment->app_idx]->sfas_ind ?? null;
        }

        return $payments;
    }

    /**
     * @param object $record
     *
     * @return string
     */
    private function prepareCsvLine(object $record): string
    {
        $csvValues = [
            $record->application_number,
            $record->sin,
            $record->postal_code,
            $record->birth_date,
            '"'.$record->first_name.'"',
            '"'.$record->middle_name.'"',
            '"'.$record->last_name.'"',
            $record->assessed_need_amount,
            $record->total_unmet_need,
            $record->weeks_of_study,
            $record->weekly_unmet_need,
            $record->program_year,
            '"'.$record->street_address1.'"',
            '"'.$record->street_address2.'"',
            '"'.$record->city.'"',
            '"'.$record->province.'"',
            $record->gender,
            $record->phone_number,
            $record->study_start_date,
            $record->study_end_date,
            '"'.$record->institution_name.'"',
            $record->program_code,
            $record->inst_code,
            '"'.$record->area_of_study.'"',
            $record->degree_level,
            $record->bursary_period_id,
            $record->month_overlap,
            $record->num_day_overlap,
            $record->valid_institution,
            $record->restriction,
            $record->awarded_in_prior_year,
            $record->withdrawal,
            $record->nurse_type,
            $record->sector,
            $record->eligibility,
            $record->neb_ineligible_reason,
            $record->rank_by_unmet_need,
            $record->rank_by_nurse_type,
            $record->rank_by_sector,
            $record->award_or_deny,
            $record->neb_deny_reason,
            $record->award_amount,
            $record->sfas_award_id,
            $record->supplier_no,
        ];

        return implode(',', $csvValues);
    }
}
