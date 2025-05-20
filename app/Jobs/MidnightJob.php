<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Modules\Lfp\Entities\Intake;
use Modules\Lfp\Entities\Lfp;
use Modules\Lfp\Entities\Payment;
use Modules\Lfp\Entities\Util;
use Modules\Lfp\Http\Controllers\LfpController;

class MidnightJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Log::info('LFP Midnight Job Started');

        $last_sync = Util::where('field_type', 'Last Sync')->first();


        // Call the function sync from LfpController
        $this->sync();

        $last_sync->field_name = Carbon::now();
        $last_sync->save();

        \Log::info('LFP Midnight Job Finished');

    }


    private function sync($status = true, $newApp = 0)
    {
        \Log::info('Starting Sync');
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600); // 10 minutes

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


        return null;
    }
}
