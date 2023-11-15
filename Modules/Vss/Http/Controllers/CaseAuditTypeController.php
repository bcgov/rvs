<?php

namespace Modules\Vss\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Modules\Vss\Entities\CaseAuditType;
use Modules\Vss\Entities\Incident;

class CaseAuditTypeController extends Controller
{
    public function deleteAuditType(Request $request, Incident $case)
    {
        CaseAuditType::where('incident_id', $case->incident_id)
            ->where('area_of_audit_code', $request->area_of_audit_code)
            ->where('audit_type', $request->audit_type)
            ->delete();

        return Redirect::route('vss.cases.edit', [$case->id]);
    }
}
