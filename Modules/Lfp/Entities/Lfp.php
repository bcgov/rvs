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
        'application_id', 'sin', 'profession', 'employer', 'employment_status', 'community', 'declined_removed_reason',
        'app_idx',
    ];

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('Modules\Lfp\Entities\Payment', 'lfp_id', 'id');
    }

    public function applications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('Modules\Lfp\Entities\Application', 'lfp_id', 'id');
    }

    public function getSfasIndAttribute(): ?object
    {
        $sin = $this->attributes['sin'];

        $awayInd = DB::connection('oracle')
            ->select(env("LFP_QUERY1") . $sin);

        // Convert the result to an object
        return $awayInd ? (object) $awayInd[0] : null;
    }

    public function getSfasAppAttribute(): ?object
    {
        $appId = $this->attributes['app_idx'];
        if(is_null($appId)) return null;

        $awayPayment = DB::connection('oracle')
            ->select(env("LFP_SFA_APP") . $appId);

        // Convert the result to an object
        return $awayPayment ? (object) $awayPayment[0] : null;
    }
}
