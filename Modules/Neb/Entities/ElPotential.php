<?php

namespace Modules\Neb\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
