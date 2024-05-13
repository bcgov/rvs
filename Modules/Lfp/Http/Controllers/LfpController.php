<?php

namespace Modules\Lfp\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Modules\Lfp\Entities\Application;
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
        $last_sync = Lfp::select('id', 'sin', 'app_idx', 'created_at')
            ->where('created_at', '!=', null)
            ->orderBy('created_at', 'desc')->first();

        $hours_difference = 2;
        if(!is_null($last_sync)){
            // Calculate the difference in hours
            $hours_difference = Carbon::parse($last_sync->created_at)->diffInHours(Carbon::now());
        }

        // Check if the difference is greater than 1 hour
        if ($hours_difference > 1) {
            // Sync applications
            //$this->sync();
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
                $check = Lfp::firstOrCreate([
                    'sin' => $app->sin,
                    'app_idx' => $app->pl_loan_forgiveness_app_idx,
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
        }


        return Redirect::route('lfp.applications.index');
    }

//$payments = Payment::whereNotNull('pay_idx')->get();
//foreach($payments as $pay){
//$qry = env("LFP_SFA_PAY_TBL") . "($pay->pay_idx)";
//$sfas_payments = DB::connection('oracle')->select($qry);
//foreach($sfas_payments as $spay){
//$pay->anniversary_date = $spay->pl_anniversary_dte;
//$pay->proposed_pay_amount = $spay->pl_dire_principal_pay_amt;
//$pay->proposed_hrs_of_service = $spay->hrs_of_service;
//$pay->sfas_pay_status = $spay->pl_payment_status_code;
//$pay->oc_pay_status = $spay->pl_payment_status_code;
//$pay->save();
//}
//}

/**
     * Show the specified resource.
     * @param Lfp $lfp
     * @return \Inertia\Response
     */
    public function show(Lfp $lfp)
    {
        $lfp = Lfp::with('payments')->where('id', $lfp->id)->first();

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
            $lfps = $lfps->where('first_name', 'ILIKE', '%'.request()->filter_fname.'%');
        }

        if (request()->filter_sin !== null) {
            $lfps = $lfps->where('sin', request()->filter_sin);
        }

        if (request()->filter_lname !== null) {
            $lfps = $lfps->where('last_name', 'ILIKE', '%'.request()->filter_lname.'%');
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

        // inject individual data from sfas
        $sins = $lfps->pluck('sin');

        $sfasInd = (new Lfp)->sfasInd($sins->toArray());

        foreach ($lfps as $lfp) {
            $lfp->sfas_ind = null;
            foreach($sfasInd as $ind){
                if($lfp->sin == $ind->sin){
                    $lfp->sfas_ind = $ind;
                    break;
                }
            }
        }

        // inject app data from sfas
        $apps = $lfps->pluck('app_idx');

        $sfasApps = (new Lfp)->sfasApp($apps->toArray());

        foreach ($lfps as $lfp) {
            $lfp->sfas_app = null;
            foreach($sfasApps as $ind){
                if($lfp->app_idx == $ind->pl_loan_forgiveness_app_idx){
                    $lfp->sfas_app = $ind;
                    break;
                }
            }
        }

        return $lfps;
    }
}
