<?php

namespace Modules\Lfp\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $sin
 * @property string $first_name
 * @property string $last_name
 * @property string $profession
 * @property string $employer
 * @property string $employment_status
 * @property string $community
 * @property string $repayment_status
 * @property bool $in_good_standing
 * @property \Carbon\Carbon $repayment_start_date
 * @property float $amount_owing
 * @property string $intake_status
 * @property \Carbon\Carbon $receive_date
 * @property string $comment
 * @property \Carbon\Carbon $proposed_registration_date
 * @property string $denial_reason
 * @property string $app_idx
 * @property string $alias_name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Intake extends ModuleModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sin', 'first_name', 'last_name', 'profession', 'employer', 'employment_status', 'community', 'repayment_status',
        'in_good_standing', 'repayment_start_date', 'amount_owing', 'intake_status', 'receive_date', 'comment',
        'proposed_registration_date', 'denial_reason', 'app_idx', 'alias_name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Lfp, Intake>
     */
    public function lfp(): BelongsTo {
        return $this->belongsTo(Lfp::class, 'app_idx', 'app_idx');
    }
}
