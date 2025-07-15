<?php

namespace Modules\Neb\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $application_number
 * @property int|null $sin
 * @property string|null $postal_code
 * @property string|null $birth_date
 * @property string|null $first_name
 * @property string|null $middle_name
 * @property string|null $last_name
 * @property float|null $assessed_need_amount
 * @property float|null $total_unmet_need
 * @property int|null $weeks_of_study
 * @property float|null $weekly_unmet_need
 * @property string|null $program_year
 * @property string|null $street_address1
 * @property string|null $street_address2
 * @property string|null $city
 * @property string|null $province
 * @property string|null $gender
 * @property string|null $phone_number
 * @property string|null $study_start_date
 * @property string|null $study_end_date
 * @property string|null $institution_name
 * @property string|null $program_code
 * @property string|null $inst_code
 * @property string|null $area_of_study
 * @property string|null $degree_level
 * @property string|null $receive_date
 * @property int|null $bursary_period_id
 * @property bool|null $month_overlap
 * @property int|null $num_day_overlap
 * @property bool|null $valid_institution
 * @property bool|null $restriction
 * @property bool|null $awarded_in_prior_year
 * @property bool|null $withdrawal
 * @property string|null $nurse_type
 * @property string|null $sector
 * @property string|null $eligibility
 * @property string|null $neb_ineligible_reason
 * @property int|null $rank_by_unmet_need
 * @property int|null $rank_by_nurse_type
 * @property int|null $rank_by_sector
 * @property string|null $award_or_deny
 * @property string|null $neb_deny_reason
 * @property float|null $award_amount
 * @property int|null $sfas_award_id
 * @property int|null $supplier_no
 * @property string|null $created_by
 * @property bool $finalized
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * // Relations
 * @property Student|null $student
 * @property BursaryPeriod|null $bursaryPeriod
 */
class ElPotential extends ModuleModel
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'application_number', 'sin', 'postal_code', 'birth_date', 'receive_date', 'first_name', 'middle_name',
        'last_name', 'assessed_need_amount', 'total_unmet_need', 'weeks_of_study', 'weekly_unmet_need',
        'program_year', 'street_address1', 'street_address2', 'city', 'province', 'gender', 'phone_number',
        'study_start_date', 'study_end_date', 'institution_name', 'program_code', 'inst_code', 'area_of_study',
        'degree_level', 'bursary_period_id', 'month_overlap', 'num_day_overlap', 'valid_institution', 'restriction',
        'awarded_in_prior_year', 'withdrawal', 'nurse_type', 'sector', 'eligibility', 'neb_ineligible_reason',
        'rank_by_unmet_need', 'rank_by_nurse_type', 'rank_by_sector', 'award_or_deny', 'neb_deny_reason',
        'award_amount', 'sfas_award_id', 'supplier_no', 'created_by', 'finalized',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Student, ElPotential>
     */
    public function student(): BelongsTo {
        return $this->belongsTo('Modules\Neb\Entities\Student', 'sin', 'sin');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<BursaryPeriod, ElPotential>
     */
    public function bursaryPeriod(): BelongsTo
    {
        return $this->belongsTo('Modules\Neb\Entities\BursaryPeriod', 'bursary_period_id', 'id');
    }
}
