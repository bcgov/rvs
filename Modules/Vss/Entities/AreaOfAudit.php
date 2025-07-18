<?php

namespace Modules\Vss\Entities;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 *
 *
 * @property int $id
 * @property string $area_of_audit_code
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, CaseAuditType> $caseAuditTypes
 * @property-read int|null $case_audit_types_count
 * @property-read Collection<int, Incident> $incidents
 * @property-read int|null $incidents_count
 * @method static Builder|AreaOfAudit newModelQuery()
 * @method static Builder|AreaOfAudit newQuery()
 * @method static Builder|AreaOfAudit query()
 * @method static Builder|AreaOfAudit whereAreaOfAuditCode($value)
 * @method static Builder|AreaOfAudit whereCreatedAt($value)
 * @method static Builder|AreaOfAudit whereDescription($value)
 * @method static Builder|AreaOfAudit whereId($value)
 * @method static Builder|AreaOfAudit whereUpdatedAt($value)
 */
class AreaOfAudit extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['area_of_audit_code', 'description'];

    /**
     * @return HasMany<Incident>
     */
    public function incidents(): HasMany {
        return $this->hasMany(Incident::class, 'area_of_audit_code', 'area_of_audit_code');
    }

    /**
     * @return HasMany<CaseAuditType>
     */
    public function caseAuditTypes(): HasMany {
        return $this->hasMany(CaseAuditType::class, 'area_of_audit_code', 'area_of_audit_code');
    }
}
