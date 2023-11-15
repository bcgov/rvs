<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function incident()
    {
        return $this->belongsTo('Modules\Vss\Entities\Incident', 'incident_id', 'incident_id');
    }

    public function audit()
    {
        return $this->belongsTo('Modules\Vss\Entities\AreaOfAudit', 'area_of_audit_code', 'area_of_audit_code');
    }
}
