<?php

namespace Modules\Lfp\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lfp extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'application_id', 'direct_lend', 'risk_sharing_guaranteed', 'direct_lend_outstanding_balance', 'risk_sharing_outstanding_balance',
        'guaranteed_outstanding_balance', 'profession', 'employer', 'employment_status', 'why_choose1', 'why_choose2',
        'why_choose3', 'why_choose4', 'why_choose5', 'other_reason', 'community', 'declined_removed_reason',
        'amount_issued', 'hours', 'birth_date', 'first_name', 'last_name', 'status', 'receive_date',
        'effective_date', 'process_date'
    ];

    public function payments()
    {
        return $this->hasMany('Modules\Lfp\Entities\Payment', 'lfp_id', 'id');
    }

    public function applications()
    {
        return $this->hasMany('Modules\Lfp\Entities\Application', 'lfp_id', 'id');
    }

}
