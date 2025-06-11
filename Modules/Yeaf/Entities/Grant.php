<?php

namespace Modules\Yeaf\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $grant_id
 * @property int $student_id
 * @property int $institution_id
 * @property string $application_receive_date
 * @property string $program_code
 * @property int $program_year_id
 * @property string $study_start_date
 * @property string $study_end_date
 * @property string $creator_user_id
 * @property string $update_user_id
 * @property string $officer_user_id
 * @property string $last_evaluation_date
 * @property int|null $age
 * @property string|null $status_code
 * @property string|null $status_date
 * @property string|null $application_type
 * @property string|null $program_name
 * @property string|null $application_number
 * @property string|null $program_other_description
 * @property float|null $total_yeaf_award
 * @property string|null $cheque_batch_number
 * @property string|null $cheque_issue_date
 * @property bool|null $withdrawal
 * @property string|null $withdrawal_date
 * @property float|null $overaward
 * @property float|null $overaward_deducted_amount
 * @property string|null $custom_pending_reason
 * @property string|null $custom_denial_reason
 * @property bool|null $study_period_completion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read false $formSubmitting
 * @property-read Student $student
 * @property-read Batch|null $batch
 * @property-read User $officer
 * @property-read ProgramYear $py
 * @property-read Institution $school
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Appeal> $appeals
 * @property-read \Illuminate\Database\Eloquent\Collection<int, GrantIneligible> $grantIneligibles
 * @property-read \Illuminate\Database\Eloquent\Collection<int, GrantIneligible> $grantPendingIneligibles
 * @property-read \Illuminate\Database\Eloquent\Collection<int, GrantIneligible> $grantDeniedIneligibles
 */
class Grant extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'grant_id',
        'institution_id',
        'application_receive_date',
        'program_code',
        'program_year_id',
        'study_start_date',
        'study_end_date',
        'age',
        'officer_user_id',
        'creator_user_id',
        'update_user_id',
        'status_code',
        'status_date',
        'last_evaluation_date',
        'application_type',
        'program_name',
        'application_number',
        'program_other_description',
        'total_yeaf_award',
        'cheque_batch_number',
        'cheque_issue_date',
        'withdrawal',
        'withdrawal_date',
        'overaward',
        'overaward_deducted_amount',
        'custom_pending_reason',
        'custom_denial_reason',
        'study_period_completion'
    ];

    protected $appends = ['formSubmitting'];

    public function student(): BelongsTo {
        return $this->belongsTo('Modules\Yeaf\Entities\Student', 'student_id', 'student_id');
    }

    public function batch(): BelongsTo {
        return $this->belongsTo('Modules\Yeaf\Entities\Batch', 'cheque_batch_number', 'batch_number');
    }

    public function officer(): BelongsTo {
        $userModel = new User;

        return $this->belongsTo($userModel, 'officer_user_id', 'user_id');
    }

    public function py(): BelongsTo {
        return $this->belongsTo('Modules\Yeaf\Entities\ProgramYear', 'program_year_id', 'program_year_id');
    }

    public function school(): BelongsTo {
        return $this->belongsTo('Modules\Yeaf\Entities\Institution', 'institution_id', 'institution_id');
    }

    public function appeals(): HasMany {
        return $this->hasMany('Modules\Yeaf\Entities\Appeal', 'grant_id', 'grant_id');
    }

    public function grantIneligibles(): HasMany {
        return $this->hasMany('Modules\Yeaf\Entities\GrantIneligible', 'grant_id', 'grant_id');
    }

    public function grantPendingIneligibles(): HasMany {
        return $this->hasMany('Modules\Yeaf\Entities\GrantIneligible', 'grant_id', 'grant_id')->where('ineligible_code_type', 'P');
    }

    public function grantDeniedIneligibles(): HasMany {
        return $this->hasMany('Modules\Yeaf\Entities\GrantIneligible', 'grant_id', 'grant_id')->where('ineligible_code_type', 'D');
    }

    public function getFormSubmittingAttribute(): false {
        return false;
    }
}
