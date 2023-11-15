<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AreaOfAudit extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['area_of_audit_code', 'description'];

    public function incidents()
    {
        return $this->hasMany('Modules\Vss\Entities\Incident', 'area_of_audit_code', 'area_of_audit_code');
    }

    public function caseAuditTypes()
    {
        return $this->hasMany('Modules\Vss\Entities\CaseAuditType', 'area_of_audit_code', 'area_of_audit_code');
    }
}
