<?php

namespace Modules\Lfp\Entities;

use Illuminate\Support\Facades\DB;

class Payment extends ModuleModel
{
    protected $appends = ['sfas_payment_attr'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lfp_id', 'reconciled_with_payment_report_date', 'reconciled_with_galaxy_date', 'comment', 'pay_idx', 'app_idx',
        'profession', 'employer', 'employment_status', 'community',
        'anniversary_date', 'proposed_pay_date', 'proposed_pay_amount', 'proposed_hrs_of_service', 'sfas_pay_status', 'oc_pay_status',

    ];

    public function lfp()
    {
        return $this->belongsTo('Modules\Lfp\Entities\Lfp', 'lfp_id', 'id');
    }

    public function sfasPayment(Array $payIds): array|null
    {
        if(empty($payIds)) return null;

        return DB::connection('oracle')
            ->select(env("LFP_SFA_PAY_TBL") . "(" . implode(",", $payIds) . ")");
    }



    public function getSfasPaymentAttrAttribute(): object|null
    {
        return is_null($this->pay_idx) ? null : $this->sfasPayment([$this->pay_idx])[0];
    }
}
