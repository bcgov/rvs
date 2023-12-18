<?php

namespace Modules\Lfp\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lfp_id', 'payment_date', 'direct_lend_payment_amount', 'direct_lend_interest_payment_amount', 'risk_sharing_payment_amount',
        'risk_sharing_interest_payment_amount', 'guaranteed_payment_amount', 'entered_in_sfas_date',
        'reconciled_with_payment_report_date', 'reconciled_with_galaxy_date', 'comment', 'amount_issued', 'reported_hours',
        'employment_letter_provided', 'anniversary_date',
    ];

    public function lfp()
    {
        return $this->belongsTo('Modules\Lfp\Entities\Lfp', 'lfp_id', 'id');
    }

}
