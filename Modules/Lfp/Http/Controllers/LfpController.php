<?php

namespace Modules\Lfp\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Modules\Lfp\Entities\Intake;
use Modules\Lfp\Entities\Lfp;
use Modules\Lfp\Entities\Payment;
use Modules\Lfp\Entities\Util;
use Modules\Lfp\Http\Requests\LfpEditRequest;
use Response;

class LfpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index($status = true, $newApp = 0)
    {
        $lfps = $this->paginateLfps();
        $last_sync = Util::where('field_type', 'Last Sync')->first();

        $hours_difference = 3;
        if(!is_null($last_sync)){
            // Calculate the difference in hours
            $hours_difference = Carbon::parse($last_sync->field_name)->diffInHours(Carbon::now());
        }

        // Check if the difference is greater than 2 hour
        if ($hours_difference > 2) {
            // Sync applications
            $this->sync();
            $last_sync->field_name = Carbon::now();
            $last_sync->save();
        }
        $last_sync = Carbon::parse($last_sync->created_at)->format('Y-m-d H:i');

        return Inertia::render('Lfp::Applications', ['page' => 'applications', 'lastSync' => $last_sync,
            'status' => $status, 'results' => $lfps, 'app' => $newApp]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(LfpEditRequest $request, Lfp $lfp)
    {
        Lfp::where('id', $lfp->id)->update($request->validated());
        $lfp = Lfp::find($lfp->id);

        return Redirect::route('lfp.applications.show', [$lfp->id]);
    }

    public function sync($status = true, $newApp = 0)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 60); // 60 seconds

        $qry = env("LFP_APP_SYNC") . "0";
        //select last app entered
        $lfp = Lfp::select('id', 'app_idx', 'sin')->whereNotNull('app_idx')->orderBy('created_at', 'desc')->first();
        if(!is_null($lfp)){
            $qry = env("LFP_APP_SYNC") . $lfp->app_idx;
        }
        $sfas = DB::connection('oracle')->select($qry);

        foreach ($sfas as $app){
            $check = Lfp::select('id', 'app_idx', 'sin')->where('app_idx', $app->pl_loan_forgiveness_app_idx)->first();
            if(is_null($check)) {
                // Only check for the sin and app_idx. Do not include first and last name
                $check = Lfp::firstOrCreate([
                    'sin' => $app->sin,
                    'app_idx' => $app->pl_loan_forgiveness_app_idx,
                ]);
                $check->update([
                    'first_name' => $app->first_name,
                    'last_name' => $app->last_name,
                ]);
            }

            $qry = env("LFP_SFA_APP_PAY") . $app->pl_loan_forgiveness_app_idx;
            $sfas_payments = DB::connection('oracle')->select($qry);
            foreach($sfas_payments as $spay){
                $checkPayment = Payment::where('app_idx', $spay->pl_loan_forgiveness_app_idx)
                    ->where('pay_idx', $spay->pl_loan_forgiveness_pay_idx)->count();
                if($checkPayment === 0){
                    $pay = Payment::firstOrNew(['pay_idx' => $spay->pl_loan_forgiveness_pay_idx]);
                    $pay->lfp_id = $check->id;
                    $pay->app_idx = $spay->pl_loan_forgiveness_app_idx;
                    $pay->pay_idx = $spay->pl_loan_forgiveness_pay_idx;
                    $pay->anniversary_date = $spay->pl_anniversary_dte;
                    $pay->proposed_pay_amount = $spay->pl_dire_principal_pay_amt;
                    $pay->proposed_hrs_of_service = $spay->hrs_of_service;
                    $pay->sfas_pay_status = $spay->pl_payment_status_code;
                    $pay->oc_pay_status = 'Pending';
                    $pay->save();
                }
            }

            // find any intake of the same sin that has already been registered but not connected to an sfas app
            $intake = Intake::where('sin', $app->sin)
                ->where('app_idx', null)
                ->where('intake_status', 'Registered')
                ->first();
            if(!is_null($intake)){
                $intake->app_idx = $app->pl_loan_forgiveness_app_idx;
                $intake->save();
            }
        }


        return Redirect::route('lfp.applications.index');
    }

    /**
     * Show the specified resource.
     * @param Lfp $lfp
     * @return \Inertia\Response
     */
    public function show(Lfp $lfp)
    {
        $lfp = Lfp::with('payments', 'intake')->where('id', $lfp->id)->first();

        $qry = env('LFP_QUERY1').$lfp->sin;
        $student = DB::connection('oracle')->select($qry);

        $application = [];
        if(!is_null($lfp->app_idx)){
            $qry = env('LFP_SFA_APP').$lfp->app_idx;
            $application = DB::connection('oracle')->select($qry);
        }
        $utils_array = [];
        foreach(Util::where('active_flag', true)->orderBy('field_name', 'asc')->get() as $u){
            $utils_array[$u->field_type][] = $u->field_name;
        }


        return Inertia::render('Lfp::Application', ['status' => true, 'result' => $lfp,
            'student' => $student, 'app' => $application, 'utils' => $utils_array]);
    }

    private function paginateLfps()
    {
        $lfps = Lfp::where('app_idx', '!=', null);
        if (request()->filter_fname !== null) {
            $lfps = $lfps->where('first_name', 'ILIKE', '%'.request()->filter_fname.'%')
                ->orWhere('full_name_alias', 'ILIKE', '%'.request()->filter_fname.'%');
            $lfps = $lfps->where('full_name_alias', 'ILIKE', '%'.request()->filter_fname.'%');
        }
        if (request()->filter_lname !== null) {
            $lfps = $lfps->where('last_name', 'ILIKE', '%'.request()->filter_lname.'%')
                ->orWhere('full_name_alias', 'ILIKE', '%'.request()->filter_lname.'%');
            $lfps = $lfps->where('full_name_alias', 'ILIKE', '%'.request()->filter_lname.'%');
        }

        if (request()->filter_sin !== null) {
            $lfps = $lfps->where('sin', request()->filter_sin);
        }

        if (request()->filter_period !== null && request()->filter_period != 'all') {
            switch (request()->filter_period){
                case 'current':
                    // Filter for the current month
                    $lfps = $lfps->whereMonth('created_at', now()->month);
                    break;

                case '3':
                    // Filter for the last 3 months
                    $lfps = $lfps->where('created_at', '>=', Carbon::now()->subMonths(3));
                    break;

                case '6':
                    // Filter for the last 6 months
                    $lfps = $lfps->where('created_at', '>=', Carbon::now()->subMonths(6));
                    break;

                case '12':
                    // Filter for the last 12 months
                    $lfps = $lfps->where('created_at', '>=', Carbon::now()->subMonths(12));
                    break;

                default:
                    break;
            }
        }

        $lfps = $lfps->orderBy('sin')->paginate(25)->onEachSide(1)->appends(request()->query());

        $newLfp = new Lfp();

        // inject individual data from sfas
        $sins = $lfps->pluck('sin');

        $sfasInd = [];
        if(!empty($sins)) {
            $rawSfasInd = $newLfp->sfasInd($sins->toArray());
            $sfasInd = collect($rawSfasInd)->keyBy('sin');
        }
        foreach ($lfps as $lfp) {
            $lfp->sfas_ind = $sfasInd[$lfp->sin] ?? null;
        }

        // inject app data from sfas
        $apps = $lfps->pluck('app_idx');

        $sfasApps = [];
        if(!empty($apps)) {
            $rawSfasApps = $newLfp->sfasApp($apps->toArray());
            $sfasApps = collect($rawSfasApps)->keyBy('pl_loan_forgiveness_app_idx');
        }
        // inject sfas app data into applications
        foreach ($lfps as $lfp) {
            $lfp->sfas_app = $sfasApps[$lfp->app_idx] ?? null;
        }

        return $lfps;
    }
}
