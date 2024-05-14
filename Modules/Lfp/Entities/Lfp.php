<?php

namespace Modules\Lfp\Entities;

use Illuminate\Support\Facades\DB;

class Lfp extends ModuleModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'application_id', 'sin', 'profession', 'employer', 'employment_status', 'community', 'declined_removed_reason',
        'app_idx', 'direct_lend', 'risk_sharing_guaranteed', 'full_name_alias',
        ];

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('Modules\Lfp\Entities\Payment', 'lfp_id', 'id')
            ->orderBy('anniversary_date', 'desc');
    }

    public function applications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('Modules\Lfp\Entities\Application', 'lfp_id', 'id');
    }

    public function sfasInd(Array $sin): array
    {
        return DB::connection('oracle')
            ->select(env("LFP_QUERY2") . "(" . implode(",", $sin) . ")");
    }

    public function sfasApp(Array $apps): array|null
    {
        if(empty($apps)) return null;

        return DB::connection('oracle')
            ->select(env("LFP_SFA_APPS") . "(" . implode(",", $apps) . ")");
    }
}
