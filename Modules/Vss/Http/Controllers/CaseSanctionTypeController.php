<?php

namespace Modules\Vss\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Modules\Vss\Entities\CaseSanctionType;
use Modules\Vss\Entities\Incident;

class CaseSanctionTypeController extends Controller
{
    public function deleteSanction(Request $request, Incident $case)
    {
        CaseSanctionType::where('incident_id', $case->incident_id)->where('sanction_code', $request->sanction_code)->delete();

        return Redirect::route('vss.cases.edit', [$case->id]);
    }
}
