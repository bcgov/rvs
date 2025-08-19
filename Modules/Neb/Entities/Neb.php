<?php

namespace Modules\Neb\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $application_id
 * @property int|null $bursary_period_id
 * @property string|null $program_code
 * @property string|null $inst_code
 * @property string|null $study_start_date
 * @property string|null $study_end_date
 * @property string|null $sfas_program_code
 * @property float|null $award_amount
 * @property string|null $declined_removed_reason Reason why application was declined or removed
 * @property int|null $sfas_award_id Random id produced by legacy NEB system
 * @property float|null $unmet_need
 * @property int|null $weeks_of_study
 * @property float|null $weekly_unmet_need
 * @property float|null $assessed_need_amount
 * @property string|null $nurse_type LPN or RN
 * @property string|null $sector public or private
 * @property bool $valid_institution Valid institutions are open, designated, BC institutions
 * @property bool $restriction Yes if borrower has one or more restrictions (including bankruptcies)
 * @property bool $awarded_in_prior_year Yes if borrower received grant in prior three bursary periods
 * @property bool $withdrawal Yes if borrower withdrew within bursary period
 * @property string|null $neb_ineligible_reason Reason why borrower was ineligible for grant
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * // Relations
 * @property Application|null $application
 * @property BursaryPeriod|null $bursaryPeriod
 */
class Neb extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'application_id', 'bursary_period_id', 'program_code', 'inst_code', 'study_start_date', 'study_end_date',
        'sfas_program_code', 'award_amount', 'declined_removed_reason', 'sfas_award_id', 'unmet_need', 'weeks_of_study',
        'weekly_unmet_need', 'assessed_need_amount', 'nurse_type', 'sector', 'valid_institution', 'restriction',
        'awarded_in_prior_year', 'withdrawal', 'neb_ineligible_reason',
    ];

    /**
     * @return BelongsTo<Application, Neb>
     */
    public function application(): BelongsTo {
        return $this->belongsTo(Application::class, 'application_id', 'application_id');
    }

    /**
     * @return BelongsTo<BursaryPeriod, Neb>
     */
    public function bursaryPeriod()
    {
        return $this->belongsTo(BursaryPeriod::class, 'bursary_period_id', 'id');
    }
}
