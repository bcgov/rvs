<?php

namespace Modules\Neb\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function application()
    {
        return $this->belongsTo('Modules\Neb\Entities\Application', 'application_id', 'application_id');
    }

    public function bursaryPeriod()
    {
        return $this->belongsTo('Modules\Neb\Entities\BursaryPeriod', 'bursary_period_id', 'id');
    }
}
