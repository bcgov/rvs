<?php

namespace Modules\Vss\Entities;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 *
 * @property int $id
 * @property int $incident_id
 * @property float|null $application_number
 * @property string $sin
 * @property string|null $last_name
 * @property string|null $first_name
 * @property int $referral_source_id
 * @property string $open_date
 * @property string|null $reactivate_date
 * @property string|null $year_of_audit
 * @property string $institution_code
 * @property string|null $auditor_user_id
 * @property string|null $audit_date
 * @property string|null $investigator_user_id
 * @property string|null $investigation_date
 * @property string $audit_type
 * @property string $area_of_audit_code
 * @property string $incident_status
 * @property string|null $close_date
 * @property bool $case_close
 * @property string|null $reason_for_closing
 * @property string|null $case_outcome
 * @property bool $appeal_flag
 * @property string|null $appeal_outcome
 * @property string|null $severity
 * @property bool $bring_forward
 * @property string|null $bring_forward_date
 * @property bool $rcmp_referral_flag
 * @property string|null $rcmp_referral_date
 * @property string|null $rcmp_closure_date
 * @property bool $charges_laid_flag
 * @property bool $conviction_flag
 * @property string|null $sentence_comment
 * @property bool $archived
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * // Appended attributes
 * @property float|null $total_award
 * @property float|null $total_prevented_funding
 * @property float|null $total_over_award
 * @property-read Collection<int, CaseAuditType> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, CaseComment> $comments
 * @property-read int|null $comments_count
 * @property-read Collection<int, CaseFunding> $funds
 * @property-read int|null $funds_count
 * @property-read Institution|null $institution
 * @property-read Collection<int, CaseNatureOffence> $offences
 * @property-read int|null $offences_count
 * @property-read AreaOfAudit $primaryAudit
 * @property-read ReferralSource $referral
 * @property-read Collection<int, CaseSanctionType> $sanctions
 * @property-read int|null $sanctions_count
 * @method static Builder|Incident active()
 * @method static Builder|Incident newModelQuery()
 * @method static Builder|Incident newQuery()
 * @method static Builder|Incident onlyTrashed()
 * @method static Builder|Incident query()
 * @method static Builder|Incident whereAppealFlag($value)
 * @method static Builder|Incident whereAppealOutcome($value)
 * @method static Builder|Incident whereApplicationNumber($value)
 * @method static Builder|Incident whereArchived($value)
 * @method static Builder|Incident whereAreaOfAuditCode($value)
 * @method static Builder|Incident whereAuditDate($value)
 * @method static Builder|Incident whereAuditType($value)
 * @method static Builder|Incident whereAuditorUserId($value)
 * @method static Builder|Incident whereBringForward($value)
 * @method static Builder|Incident whereBringForwardDate($value)
 * @method static Builder|Incident whereCaseClose($value)
 * @method static Builder|Incident whereCaseOutcome($value)
 * @method static Builder|Incident whereChargesLaidFlag($value)
 * @method static Builder|Incident whereCloseDate($value)
 * @method static Builder|Incident whereConvictionFlag($value)
 * @method static Builder|Incident whereCreatedAt($value)
 * @method static Builder|Incident whereDeletedAt($value)
 * @method static Builder|Incident whereFirstName($value)
 * @method static Builder|Incident whereId($value)
 * @method static Builder|Incident whereIncidentId($value)
 * @method static Builder|Incident whereIncidentStatus($value)
 * @method static Builder|Incident whereInstitutionCode($value)
 * @method static Builder|Incident whereInvestigationDate($value)
 * @method static Builder|Incident whereInvestigatorUserId($value)
 * @method static Builder|Incident whereLastName($value)
 * @method static Builder|Incident whereOpenDate($value)
 * @method static Builder|Incident whereRcmpClosureDate($value)
 * @method static Builder|Incident whereRcmpReferralDate($value)
 * @method static Builder|Incident whereRcmpReferralFlag($value)
 * @method static Builder|Incident whereReactivateDate($value)
 * @method static Builder|Incident whereReasonForClosing($value)
 * @method static Builder|Incident whereReferralSourceId($value)
 * @method static Builder|Incident whereSentenceComment($value)
 * @method static Builder|Incident whereSeverity($value)
 * @method static Builder|Incident whereSin($value)
 * @method static Builder|Incident whereUpdatedAt($value)
 * @method static Builder|Incident whereYearOfAudit($value)
 * @method static Builder|Incident withTrashed()
 * @method static Builder|Incident withoutTrashed()
 * @mixin \Eloquent
 */
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

    /**
     * @return HasMany<CaseFunding>
     */
    public function funds(): HasMany {
        return $this->hasMany(CaseFunding::class, 'incident_id', 'incident_id');
    }

    /**
     * @return HasMany<CaseComment>
     */
    public function comments(): HasMany {
        return $this->hasMany(CaseComment::class, 'incident_id', 'incident_id')->orderByDesc('comment_date');
    }

    /**
     * @return HasMany<CaseAuditType>
     */
    public function audits(): HasMany {
        return $this->hasMany(CaseAuditType::class, 'incident_id', 'incident_id');
    }

    /**
     * @return HasMany<CaseNatureOffence>
     */
    public function offences(): HasMany {
        return $this->hasMany(CaseNatureOffence::class, 'incident_id', 'incident_id');
    }

    /**
     * @return HasMany<CaseSanctionType>
     */
    public function sanctions(): HasMany {
        return $this->hasMany(CaseSanctionType::class, 'incident_id', 'incident_id');
    }

    /**
     * @return HasOne<Institution>
     */
    public function institution(): HasOne {
        return $this->hasOne(Institution::class, 'institution_code', 'institution_code');
    }

    /**
     * @return BelongsTo<AreaOfAudit, \Modules\Vss\Entities\Incident>
     */
    public function primaryAudit(): BelongsTo {
        return $this->belongsTo(AreaOfAudit::class, 'area_of_audit_code', 'area_of_audit_code');
    }

    /**
     * @return BelongsTo<ReferralSource, \Modules\Vss\Entities\Incident>
     */
    public function referral(): BelongsTo {
        return $this->belongsTo(ReferralSource::class, 'referral_source_id', 'id');
    }

    /**
     * @return int
     */
    public function getTotalOverAwardAttribute()
    {
        $total = 0;
        foreach ($this->funds as $fund) {
            $total += $fund->over_award;
        }

        return $total;
    }

    /**
     * @return int
     */
    public function getTotalPreventedFundingAttribute()
    {
        $total = 0;
        foreach ($this->funds as $fund) {
            $total += $fund->prevented_funding;
        }

        return $total;
    }

    /**
     * @return float|null
     */
    public function getTotalAwardAttribute()
    {
        return $this->total_prevented_funding + $this->total_over_award;
    }

    /**
     * Scope a query to only include admin users.
     *
     * @param Builder<\Modules\Vss\Entities\Incident> $query
     * @return Builder<\Modules\Vss\Entities\Incident>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('archived', false);
    }

    /**
     * @param Builder<\Modules\Vss\Entities\Incident> $query
     * @return Builder<\Modules\Vss\Entities\Incident>
     */
    public function scopeIsActive(Builder $query): Builder
    {
        return $query->where('archived', false);
    }

    /**
     * @param Builder<\Modules\Vss\Entities\Incident> $query
     * @return Builder<\Modules\Vss\Entities\Incident>
     */
    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('archived', true);
    }

}
