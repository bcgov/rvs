<?php

namespace Modules\Lfp\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Lfp\Entities\Intake;
use Modules\Lfp\Entities\Util;
use Modules\Lfp\Http\Requests\IntakeEditRequest;
use Modules\Lfp\Http\Requests\IntakeStoreRequest;

class IntakeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(): Response {
        $intakes = $this->paginateIntakes();

        return Inertia::render('Lfp::Intakes', ['page' => 'intakes', 'results' => $intakes]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(): Response {
        $utils_array = [];
        $utils = Util::whereIn('field_type', ['Profession', 'Employer', 'Community', 'Employment Status'])
            ->where('active_flag', true)->orderBy('field_name', 'asc')->get();
        foreach($utils as $u){
            $utils_array[$u->field_type][] = $u->field_name;
        }

        return Inertia::render('Lfp::IntakeNew', ['status' => true, 'utils' => $utils_array]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param IntakeStoreRequest $request
     *
     * @return Response
     */
    public function store(IntakeStoreRequest $request): Response {
        $intake = Intake::create($request->validated());
        $intakes = $this->paginateIntakes();

        return Inertia::render('Lfp::Intakes', ['page' => 'intakes', 'results' => $intakes]);
    }

    /**
     * Show the specified resource.
     * @param Intake $intake
     * @return Response
     */
    public function show(Intake $intake): Response {
        //$intake = Intake::where('id', $intake->id)->first();
        $utils_array = [];
        $utils = Util::whereIn('field_type', ['Profession', 'Employer', 'Community', 'Employment Status', 'Remove Reason'])
            ->where('active_flag', true)->orderBy('field_name', 'asc')->get();
        foreach($utils as $u){
            $utils_array[$u->field_type][] = $u->field_name;
        }

        return Inertia::render('Lfp::IntakeEdit', ['status' => true, 'result' => $intake,
            'utils' => $utils_array]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param IntakeEditRequest $request
     * @param Intake $intake
     *
     * @return RedirectResponse
     */
    public function update(IntakeEditRequest $request, Intake $intake): RedirectResponse {
        Intake::where('id', $intake->id)->update($request->validated());

        return Redirect::route('lfp.intakes.show', [$intake->id]);
    }

    /**
     * @return LengthAwarePaginator<Intake>
     */
    private function paginateIntakes(): LengthAwarePaginator
    {
        $intakes = new Intake();

        if (request()->filter_sin !== null) {
            $intakes = $intakes->where('sin', request()->filter_sin);
        }

        if (request()->filter_fname !== null) {
            $intakes = $intakes->where('first_name', 'ILIKE', '%'.request()->filter_fname.'%');
        }
        if (request()->filter_lname !== null) {
            $intakes = $intakes->where('last_name', 'ILIKE', '%'.request()->filter_lname.'%');
        }

        if (request()->filter_status !== null && request()->filter_status !== 'all') {
            $intakes = $intakes->where('intake_status', request()->filter_status);
        }

        if (request()->sort !== null) {
            $intakes = $intakes->orderBy(request()->sort, request()->direction);
        } else {
            $intakes = $intakes->orderBy('proposed_registration_date', 'desc');
        }

        return $intakes->paginate(25)->onEachSide(1)->appends(request()->query());
    }
  
    /* Export intake applications.
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request, $filterType = 'All')
    {
        if($filterType !== 'All') {
            $intakes = Intake::where('intake_status', $filterType)->get();
        } else {
            $intakes = Intake::all();
        }


        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="intakes.csv"',
        ];

        $callback = function() use ($intakes) {
            $file = fopen('php://output', 'w');
            // Add CSV header
            // All the columns from the intake model
            $header = ['First Name', 'Last Name', 'Profession', 'Employer', 'Employment Status', 'Community', 'Repayment Status',
                'In Good Standing', 'Repayment Start Date', 'Amount Owing', 'Intake Status', 'Receive Date', 'Comment',
                'Proposed Registration Date', 'Denial Reason', 'App Index', 'Alias Name'];
            fputcsv($file, $header);

            foreach ($intakes as $intake) {
                fputcsv($file, [
                    $intake->first_name,
                    $intake->last_name,
                    $intake->profession,
                    $intake->employer,
                    $intake->employment_status,
                    $intake->community,
                    $intake->repayment_status,
                    $intake->in_good_standing,
                    $intake->repayment_start_date,
                    $intake->amount_owing,
                    $intake->intake_status,
                    $intake->receive_date,
                    $intake->comment,
                    $intake->proposed_registration_date,
                    $intake->denial_reason,
                    $intake->app_idx,
                    $intake->alias_name,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
