<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;

class Incident extends ModuleModel
{
    use SoftDeletes;

    protected $appends = ['total_award', 'total_prevented_funding', 'total_over_award'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['sin', 'incident_id', 'institution_code', 'incident_status', 'severity', 'year_of_audit', 'audit_type', 'area_of_audit_code',
        'referral_source_id', 'open_date', 'last_name', 'first_name', 'application_number', 'reactivate_date',
        'auditor_user_id', 'audit_date', 'investigator_user_id', 'investigation_date', 'bring_forward', 'bring_forward_date',
        'appeal_flag', 'appeal_outcome', 'case_close', 'close_date', 'reason_for_closing', 'case_outcome',
        'rcmp_referral_flag', 'rcmp_referral_date', 'rcmp_closure_date', 'charges_laid_flag',
        'conviction_flag', 'sentence_comment',
    ];

    public function funds()
    {
        return $this->hasMany('Modules\Vss\Entities\CaseFunding', 'incident_id', 'incident_id');
    }

    public function comments()
    {
        return $this->hasMany('Modules\Vss\Entities\CaseComment', 'incident_id', 'incident_id')->orderByDesc('comment_date');
    }

    public function audits()
    {
        return $this->hasMany('Modules\Vss\Entities\CaseAuditType', 'incident_id', 'incident_id');
    }

    public function offences()
    {
        return $this->hasMany('Modules\Vss\Entities\CaseNatureOffence', 'incident_id', 'incident_id');
    }

    public function sanctions()
    {
        return $this->hasMany('Modules\Vss\Entities\CaseSanctionType', 'incident_id', 'incident_id');
    }

    public function institution()
    {
        return $this->hasOne('Modules\Vss\Entities\Institution', 'institution_code', 'institution_code');
    }

    public function primaryAudit()
    {
        return $this->belongsTo('Modules\Vss\Entities\AreaOfAudit', 'area_of_audit_code', 'area_of_audit_code');
    }

    public function referral()
    {
        return $this->belongsTo('Modules\Vss\Entities\ReferralSource', 'referral_source_id', 'id');
    }

    public function getTotalOverAwardAttribute()
    {
        $total = 0;
        foreach ($this->funds as $fund) {
            $total += $fund->over_award;
        }

        return $total;
    }

    public function getTotalPreventedFundingAttribute()
    {
        $total = 0;
        foreach ($this->funds as $fund) {
            $total += $fund->prevented_funding;
        }

        return $total;
    }

    public function getTotalAwardAttribute()
    {
        return $this->total_prevented_funding + $this->total_over_award;
    }

    /**
     * Scope a query to only include admin users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('archived', false);
    }

    public function scopeIsActive($query)
    {
        return $query->where('archived', false);
    }

    public function scopeArchived($query)
    {
        return $query->where('archived', true);
    }
}
