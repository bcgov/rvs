<?php

namespace Modules\Lfp\Entities;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property int|null $lfp_id
 * @property string|null $app_idx
 * @property string|null $pay_idx
 * @property \Carbon\Carbon|null $anniversary_date
 * @property float|null $proposed_pay_amount
 * @property int|null $proposed_hrs_of_service
 * @property string|null $sfas_pay_status
 * @property string|null $oc_pay_status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Lfp, Payment>
     */
    public function lfp(): BelongsTo {
        return $this->belongsTo('Modules\Lfp\Entities\Lfp', 'lfp_id', 'id');
    }

    /**
     * @param array<int, string> $payIds
     *
     * @return array<object>|null
     */
    public function sfasPayment(Array $payIds): array|null
    {
        if(empty($payIds)) return null;

        return DB::connection('oracle')
            ->select(env("LFP_SFA_PAY_TBL") . "(" . implode(",", $payIds) . ")");
    }


    /**
     * @return object|null
     */
    public function getSfasPaymentAttrAttribute(): object|null
    {
        return is_null($this->pay_idx) ? null : $this->sfasPayment([$this->pay_idx])[0];
    }
}
