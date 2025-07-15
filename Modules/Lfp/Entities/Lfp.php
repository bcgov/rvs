<?php

namespace Modules\Lfp\Entities;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string|null $application_id
 * @property string|null $sin
 * @property string|null $profession
 * @property string|null $employer
 * @property string|null $employment_status
 * @property string|null $community
 * @property string|null $declined_removed_reason
 * @property string|null $app_idx
 * @property bool|null $direct_lend
 * @property bool|null $risk_sharing_guaranteed
 * @property string|null $full_name_alias
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $comment
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class Lfp extends ModuleModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'application_id', 'sin', 'profession', 'employer', 'employment_status', 'community', 'declined_removed_reason',
        'app_idx', 'direct_lend', 'risk_sharing_guaranteed', 'full_name_alias', 'first_name', 'last_name', 'comment',
        ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Payment>
     */
    public function payments(): HasMany
    {
        return $this->hasMany('Modules\Lfp\Entities\Payment', 'lfp_id', 'id')
            ->orderBy('anniversary_date', 'desc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Application>
     */
    public function applications(): HasMany
    {
        return $this->hasMany('Modules\Lfp\Entities\Application', 'lfp_id', 'id');
    }

    /**
     * @param array<string> $sin
     *
     * @return array<object>|null
     */
    public function sfasInd(Array $sin): array|null
    {
        if(empty($sin)) return null;

        return DB::connection('oracle')
            ->select(env("LFP_QUERY2") . "(" . implode(",", $sin) . ")");
    }

    /**
     * @param array<string> $apps
     *
     * @return array<object>|null
     */
    public function sfasApp(Array $apps): array|null
    {
        if(empty($apps)) return null;

        return DB::connection('oracle')
            ->select(env("LFP_SFA_APPS") . "(" . implode(",", $apps) . ")");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<Intake>
     */
    public function intake(): HasOne
    {
        return $this->hasOne(Intake::class, 'app_idx', 'app_idx');
    }
}
