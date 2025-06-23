<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AreaOfAudit extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['area_of_audit_code', 'description'];

    public function incidents(): HasMany {
        return $this->hasMany('Modules\Vss\Entities\Incident', 'area_of_audit_code', 'area_of_audit_code');
    }

    public function caseAuditTypes(): HasMany {
        return $this->hasMany('Modules\Vss\Entities\CaseAuditType', 'area_of_audit_code', 'area_of_audit_code');
    }
}
