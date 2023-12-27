<?php

namespace Modules\Lfp\Entities;


use Illuminate\Support\Facades\DB;

class Lfp extends ModuleModel
{
    protected $appends = ['sfas_ind', 'sfas_app'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'application_id', 'direct_lend', 'risk_sharing_guaranteed', 'direct_lend_outstanding_balance', 'risk_sharing_outstanding_balance',
        'guaranteed_outstanding_balance', 'profession', 'employer', 'employment_status', 'why_choose1', 'why_choose2',
        'why_choose3', 'why_choose4', 'why_choose5', 'other_reason', 'community', 'declined_removed_reason',
        'amount_issued', 'hours', 'birth_date', 'first_name', 'last_name', 'status', 'receive_date', 'app_idx',
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


    public function getSfasIndAttribute()
    {
        $sin = $this->attributes['sin'];

        $awayPayment = DB::connection('oracle')
            ->select(env("LFP_QUERY1") . $sin);

        // Convert the result to an object
        return $awayPayment ? (object) $awayPayment[0] : null;
    }
    public function getSfasAppAttribute()
    {
        $appId = $this->attributes['app_idx'];

        $awayPayment = DB::connection('oracle')
            ->select(env("LFP_SFA_APP") . $appId);

        // Convert the result to an object
        return $awayPayment ? (object) $awayPayment[0] : null;
    }

}
