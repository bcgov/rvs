<?php

namespace Modules\Yeaf\Entities;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $grant_id
 * @property int $student_id
 * @property int|null $institution_id
 * @property string $application_receive_date
 * @property string|null $program_code
 * @property int $program_year_id
 * @property string|null $study_start_date
 * @property string|null $study_end_date
 * @property string $creator_user_id
 * @property string|null $update_user_id
 * @property string|null $officer_user_id
 * @property string $last_evaluation_date
 * @property int|null $age
 * @property string|null $status_code
 * @property string|null $status_date
 * @property string|null $application_type
 * @property string|null $program_name
 * @property int|null $application_number
 * @property string|null $program_other_description
 * @property float $eligible_need
 * @property float $total_award
 * @property float $unmet_need
 * @property float $total_bcsl_award
 * @property float $total_yeaf_award
 * @property float $total_yeaf_award_remit
 * @property float $overaward
 * @property float $overaward_calc
 * @property float $overaward_deducted_amount
 * @property string|null $reason_for_ineligibility
 * @property string|null $date_issued_month
 * @property string|null $date_issued_year
 * @property string|null $letter_text
 * @property string|null $custom_pending_reason
 * @property string|null $custom_denial_reason
 * @property bool $study_period_completion
 * @property bool $confirmation_bcsl_remission
 * @property bool $reassess
 * @property bool $overaward_cleared
 * @property bool $withdrawal
 * @property string|null $bcsl_remission
 * @property string|null $letter_date
 * @property string|null $cheque_issue_date
 * @property string|null $withdrawal_date
 * @property string|null $last_letter_produced_date
 * @property string|null $cheque_batch_number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read false $formSubmitting
 * @property-read Student $student
 * @property-read Batch|null $batch
 * @property-read User|null $officer
 * @property-read ProgramYear $py
 * @property-read Institution|null $school
 * @property-read Collection<int, Appeal> $appeals
 * @property-read Collection<int, GrantIneligible> $grantIneligibles
 * @property-read Collection<int, GrantIneligible> $grantPendingIneligibles
 * @property-read Collection<int, GrantIneligible> $grantDeniedIneligibles
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
    'eligible_need',
    'total_award',
    'unmet_need',
    'total_bcsl_award',
    'total_yeaf_award',
    'total_yeaf_award_remit',
    'overaward',
    'overaward_calc',
    'overaward_deducted_amount',
    'reason_for_ineligibility',
    'date_issued_month',
    'date_issued_year',
    'letter_text',
    'custom_pending_reason',
    'custom_denial_reason',
    'study_period_completion',
    'confirmation_bcsl_remission',
    'reassess',
    'overaward_cleared',
    'withdrawal',
    'bcsl_remission',
    'letter_date',
    'cheque_issue_date',
    'withdrawal_date',
    'last_letter_produced_date',
    'cheque_batch_number'
];

    protected $appends = ['formSubmitting'];

    /**
     * @return BelongsTo<Student, Grant>
     */
    public function student(): BelongsTo {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    /**
     * @return BelongsTo<Batch, Grant>
     */
    public function batch(): BelongsTo {
        return $this->belongsTo(Batch::class, 'cheque_batch_number', 'batch_number');
    }

    /**
     * @return BelongsTo<User, Grant>
     */
    public function officer(): BelongsTo {
        $userModel = new User;

        return $this->belongsTo($userModel, 'officer_user_id', 'user_id');
    }

    /**
     * @return BelongsTo<ProgramYear, Grant>
     */
    public function py(): BelongsTo {
        return $this->belongsTo(ProgramYear::class, 'program_year_id', 'program_year_id');
    }

    /**
     * @return BelongsTo<Institution, Grant>
     */
    public function school(): BelongsTo {
        return $this->belongsTo(Institution::class, 'institution_id', 'institution_id');
    }

    /**
     * @return HasMany<Appeal>
     */
    public function appeals(): HasMany {
        return $this->hasMany(Appeal::class, 'grant_id', 'grant_id');
    }

    /**
     * @return HasMany<GrantIneligible>
     */
    public function grantIneligibles(): HasMany {
        return $this->hasMany(GrantIneligible::class, 'grant_id', 'grant_id');
    }

    /**
     * @return HasMany<GrantIneligible>
     */
    public function grantPendingIneligibles(): HasMany {
        return $this->hasMany(GrantIneligible::class, 'grant_id', 'grant_id')->where('ineligible_code_type', 'P');
    }

    /**
     * @return HasMany<GrantIneligible>
     */
    public function grantDeniedIneligibles(): HasMany {
        return $this->hasMany(GrantIneligible::class, 'grant_id', 'grant_id')->where('ineligible_code_type', 'D');
    }

    public function getFormSubmittingAttribute(): false {
        return false;
    }
}
