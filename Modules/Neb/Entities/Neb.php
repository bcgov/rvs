<?php

namespace Modules\Neb\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Application, Neb>
     */
    public function application(): BelongsTo {
        return $this->belongsTo('Modules\Neb\Entities\Application', 'application_id', 'application_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<BursaryPeriod, Neb>
     */
    public function bursaryPeriod()
    {
        return $this->belongsTo('Modules\Neb\Entities\BursaryPeriod', 'bursary_period_id', 'id');
    }
}
