<?php

namespace Modules\Vss\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Modules\Vss\Entities\CaseNatureOffence;
use Modules\Vss\Entities\Incident;

class CaseNatureOffenceController extends Controller
{
    public function deleteOffence(Request $request, Incident $case): RedirectResponse {
        CaseNatureOffence::where('incident_id', $case->incident_id)->where('nature_code', $request->nature_code)->delete();

        return Redirect::route('vss.cases.edit', [$case->id]);
    }
}
