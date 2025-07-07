<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property string $area_of_audit_code
 * @property int $incident_id
 * @property string $audit_type
 * @property-read \Modules\Vss\Entities\AreaOfAudit $audit
 * @property-read \Modules\Vss\Entities\Incident $incident
 * @method static \Illuminate\Database\Eloquent\Builder|CaseAuditType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseAuditType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseAuditType query()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseAuditType whereAreaOfAuditCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseAuditType whereAuditType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseAuditType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseAuditType whereIncidentId($value)
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
     * @return BelongsTo<\Modules\Vss\Entities\Incident, \Modules\Vss\Entities\CaseAuditType>
     */
    public function incident(): BelongsTo {
        return $this->belongsTo('Modules\Vss\Entities\Incident', 'incident_id', 'incident_id');
    }

    /**
     * @return BelongsTo<\Modules\Vss\Entities\AreaOfAudit, \Modules\Vss\Entities\CaseAuditType>
     */
    public function audit(): BelongsTo {
        return $this->belongsTo('Modules\Vss\Entities\AreaOfAudit', 'area_of_audit_code', 'area_of_audit_code');
    }
}
