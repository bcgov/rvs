<?php

namespace Modules\Lfp\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Modules\Lfp\Entities\Intake;
use Modules\Lfp\Entities\Util;
use Modules\Lfp\Http\Requests\IntakeEditRequest;
use Modules\Lfp\Http\Requests\IntakeStoreRequest;

class IntakeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Inertia\Response
     */
    public function index()
    {
        $intakes = $this->paginateIntakes();

        return Inertia::render('Lfp::Intakes', ['page' => 'intakes', 'results' => $intakes]);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Inertia\Response
     */
    public function create()
    {
        $utils_array = [];
        foreach(Util::where('active_flag', true)->orderBy('field_name', 'asc')->get() as $u){
            $utils_array[$u->field_type][] = $u->field_name;
        }

        return Inertia::render('Lfp::IntakeNew', ['status' => true, 'utils' => $utils_array]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Inertia\Response
     */
    public function store(IntakeStoreRequest $request)
    {
        $intake = Intake::create($request->validated());
        $intakes = $this->paginateIntakes();

        return Inertia::render('Lfp::Intakes', ['page' => 'intakes', 'results' => $intakes]);
    }

    /**
     * Show the specified resource.
     * @param Intake $intake
     * @return \Inertia\Response
     */
    public function show(Intake $intake)
    {
        $intake = Intake::where('id', $intake->id)->first();
        $utils_array = [];
        foreach(Util::where('active_flag', true)->orderBy('field_name', 'asc')->get() as $u){
            $utils_array[$u->field_type][] = $u->field_name;
        }

        return Inertia::render('Lfp::IntakeEdit', ['status' => true, 'result' => $intake,
            'utils' => $utils_array]);

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(IntakeEditRequest $request, Intake $intake)
    {
        Intake::where('id', $intake->id)->update($request->validated());

        return Redirect::route('lfp.intakes.show', [$intake->id]);
    }

    private function paginateIntakes()
    {
        $intakes = new Intake();

        if (request()->filter_fname !== null) {
            $intakes = $intakes->where('first_name', 'ILIKE', '%'.request()->filter_fname.'%');
        }
        if (request()->filter_lname !== null) {
            $intakes = $intakes->where('last_name', 'ILIKE', '%'.request()->filter_lname.'%');
        }
        if (request()->filter_period !== null && request()->filter_period != 'all') {
            switch (request()->filter_period){
                case 'current':
                    // Filter for the current month
                    $intakes = $intakes->whereMonth('created_at', now()->month);
                    break;

                case '3':
                    // Filter for the last 3 months
                    $intakes = $intakes->where('created_at', '>=', Carbon::now()->subMonths(3));
                    break;

                case '6':
                    // Filter for the last 6 months
                    $intakes = $intakes->where('created_at', '>=', Carbon::now()->subMonths(6));
                    break;

                case '12':
                    // Filter for the last 12 months
                    $intakes = $intakes->where('created_at', '>=', Carbon::now()->subMonths(12));
                    break;

                default:
                    break;
            }
        }

        if (request()->sort !== null) {
            $intakes = $intakes->orderBy(request()->sort, request()->direction);
        } else {
            $intakes = $intakes->orderBy('created_at', 'desc');
        }

        return $intakes->paginate(25)->onEachSide(1)->appends(request()->query());
    }
}
