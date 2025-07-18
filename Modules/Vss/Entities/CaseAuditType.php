<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property string $area_of_audit_code
 * @property int $incident_id
 * @property string $audit_type
 * @property-read AreaOfAudit $audit
 * @property-read Incident $incident
 * @method static Builder|CaseAuditType newModelQuery()
 * @method static Builder|CaseAuditType newQuery()
 * @method static Builder|CaseAuditType query()
 * @method static Builder|CaseAuditType whereAreaOfAuditCode($value)
 * @method static Builder|CaseAuditType whereAuditType($value)
 * @method static Builder|CaseAuditType whereId($value)
 * @method static Builder|CaseAuditType whereIncidentId($value)
 * @mixin \Eloquent
 */
class CaseAuditType extends ModuleModel
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['incident_id', 'area_of_audit_code', 'audit_type'];

    /**
     * @return BelongsTo<Incident, \Modules\Vss\Entities\CaseAuditType>
     */
    public function incident(): BelongsTo {
        return $this->belongsTo(Incident::class, 'incident_id', 'incident_id');
    }

    /**
     * @return BelongsTo<AreaOfAudit, \Modules\Vss\Entities\CaseAuditType>
     */
    public function audit(): BelongsTo {
        return $this->belongsTo(AreaOfAudit::class, 'area_of_audit_code', 'area_of_audit_code');
    }
}
