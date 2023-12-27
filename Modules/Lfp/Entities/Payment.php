<?php

namespace Modules\Lfp\Entities;

use Illuminate\Support\Facades\DB;

class Payment extends ModuleModel
{
    protected $appends = ['sfas_payment'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lfp_id', 'reconciled_with_payment_report_date', 'reconciled_with_galaxy_date', 'comment', 'pay_idx', 'app_idx',
        'profession', 'employer', 'employment_status', 'community',
    ];

    public function lfp()
    {
        return $this->belongsTo('Modules\Lfp\Entities\Lfp', 'lfp_id', 'id');
    }

    public function getSfasPaymentAttribute()
    {
        $payId = $this->attributes['pay_idx'];

        $awayPayment = DB::connection('oracle')
            ->select(env("LFP_SFA_PAY_TBL") . $payId);

        // Convert the result to an object
        return $awayPayment ? (object) $awayPayment[0] : null;
    }

}
