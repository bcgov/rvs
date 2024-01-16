<?php

namespace Modules\Lfp\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Modules\Lfp\Entities\Application;
use Modules\Lfp\Entities\Lfp;
use Modules\Lfp\Entities\Payment;
use Modules\Lfp\Entities\Util;
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
        $lfps = new Lfp();
        $lfps = $this->paginateLfps($lfps);
        $last_sync = Lfp::select('id', 'sin', 'app_idx', 'created_at')->where('created_at', '!=', null)
            ->orderBy('created_at', 'desc')->first();
        $last_sync = Carbon::parse($last_sync->created_at)->format('Y-m-d H:i');

        return Inertia::render('Lfp::Applications', ['page' => 'applications', 'lastSync' => $last_sync,
            'status' => $status, 'results' => $lfps, 'app' => $newApp]);
    }

    public function sync($status = true, $newApp = 0)
    {
        //select last app entered
        $lfp = Lfp::select('id', 'app_idx')->orderBy('created_at', 'desc')->first();
        $qry = env("LFP_APP_SYNC") . $lfp->app_idx;
        $sfas = DB::connection('oracle')->select($qry);

        foreach ($sfas as $app){
            $check = Lfp::select('id', 'app_idx')->where('app_idx', $app->pl_loan_forgiveness_app_idx)->first();
            if(is_null($check)){
                Lfp::firstOrCreate([
                    'sin' => $app->sin,
                    'app_idx' => $app->pl_loan_forgiveness_app_idx,
                ]);

                $qry = env("LFP_SFA_APP_PAY") . $lfp->app_idx;
                $sfas_payments = DB::connection('oracle')->select($qry);
                foreach($sfas_payments as $spay){
                    $checkPayment = Payment::where('app_idx', $spay->pl_loan_forgiveness_app_idx)
                        ->where('pay_ids', $spay->pl_loan_forgiveness_pay_idx)->first();
                    if(is_null($checkPayment)){
                        $pay = Payment::firstOrNew(['lfp_id' => $app->id, 'pay_idx' => $spay->pl_loan_forgiveness_pay_idx]);
                        $pay->lfp_id = $app->id;
                        $pay->direct_lend_payment_amount = $spay->pl_dire_principal_pay_amt;
                        $pay->app_idx = $spay->pl_loan_forgiveness_app_idx;
                        $pay->pay_idx = $spay->pl_loan_forgiveness_pay_idx;
                        $pay->save();
                    }
                }
            }
        }

        $lfps = new Lfp();
        $lfps = $this->paginateLfps($lfps);

        return Inertia::render('Lfp::Applications', ['page' => 'applications', 'status' => $status, 'results' => $lfps, 'app' => $newApp]);
    }

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
        foreach(Util::where('active_flag', true)->get() as $u){
            $utils_array[$u->field_type][] = $u->field_name;
        }


        return Inertia::render('Lfp::Application', ['status' => true, 'result' => $lfp,
            'student' => $student, 'app' => $application, 'utils' => $utils_array]);
    }

    private function paginateLfps($lfps)
    {
        $lfps = $lfps->where('app_idx', '!=', null);
        if (request()->filter_fname !== null) {
            $lfps = $lfps->where('first_name', 'ILIKE', '%'.request()->filter_fname.'%');
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

        if (request()->sort !== null) {
            $lfps = $lfps->orderBy(request()->sort, request()->direction);
        } else {
            $lfps = $lfps->orderBy('created_at', 'desc');
        }

        return $lfps->paginate(25)->onEachSide(1)->appends(request()->query());
    }
}
