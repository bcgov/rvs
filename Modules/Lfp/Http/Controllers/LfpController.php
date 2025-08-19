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

        return Inertia::render('Lfp::Applications', ['page' => 'applications', 'lastSync' => $last_sync->field_name,
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


        // $lfps = $lfps->where('created_at', '>=', Carbon::now()->subMonths(12));
        if (request()->filter_period !== null) {
            switch (request()->filter_period){
                case 'current':
                    // Filter for the current month
                    $lfps = $lfps->whereMonth('created_at', now()->month);
                    \Log::info('Filtering LFPs for the current month');
                    break;

                case '3':
                    // Filter for the last 3 months
                    $lfps = $lfps->where('created_at', '>=', Carbon::now()->subMonths(3));
                    \Log::info('Filtering LFPs for the last 3 months');
                    break;

                case '6':
                    // Filter for the last 6 months
                    $lfps = $lfps->where('created_at', '>=', Carbon::now()->subMonths(6));
                    \Log::info('Filtering LFPs for the last 6 months');
                    break;

                case 'all':
                    // Filter for all time
                    $lfps = $lfps->where('created_at', '<', Carbon::now());
                    \Log::info('Filtering LFPs for all time');
                    break;

                default:
                    break;
            }
        }else{
            // Filter for the last 12 months
            $lfps = $lfps->where('created_at', '>=', Carbon::now()->subMonths(12));
            \Log::info('Filtering LFPs for the last 12 months');
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
