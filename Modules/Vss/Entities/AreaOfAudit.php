<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 *
 *
 * @property int $id
 * @property string $area_of_audit_code
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Vss\Entities\CaseAuditType> $caseAuditTypes
 * @property-read int|null $case_audit_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Vss\Entities\Incident> $incidents
 * @property-read int|null $incidents_count
 * @method static \Illuminate\Database\Eloquent\Builder|AreaOfAudit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AreaOfAudit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AreaOfAudit query()
 * @method static \Illuminate\Database\Eloquent\Builder|AreaOfAudit whereAreaOfAuditCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AreaOfAudit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AreaOfAudit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AreaOfAudit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AreaOfAudit whereUpdatedAt($value)
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
     * @return HasMany<\Modules\Vss\Entities\Incident>
     */
    public function incidents(): HasMany {
        return $this->hasMany('Modules\Vss\Entities\Incident', 'area_of_audit_code', 'area_of_audit_code');
    }

    /**
     * @return HasMany<\Modules\Vss\Entities\CaseAuditType>
     */
    public function caseAuditTypes(): HasMany {
        return $this->hasMany('Modules\Vss\Entities\CaseAuditType', 'area_of_audit_code', 'area_of_audit_code');
    }
}
