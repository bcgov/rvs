<?php

namespace Modules\Vss\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Response;
use Inertia\ResponseFactory;
use Modules\Vss\Entities\CaseFunding;
use Modules\Vss\Entities\FundingType;
use Modules\Vss\Entities\Incident;
use Modules\Vss\Entities\Institution;

class CaseFundingController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function show(string $id): Response|ResponseFactory {
        $case = Incident::with('funds.fundingType', 'institution')->findOrFail($id);
        $funds = FundingType::get();
        $schools = Institution::get();

        return inertia('Vss::CaseFunding', ['status' => true, 'result' => $case, 'funds' => $funds, 'schools' => $schools, 'now' => date('Y-m-d')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function update(Request $request, Incident $caseFunding): Response|ResponseFactory {
        foreach ($request->old_rows as $row) {
            CaseFunding::where('id', $row['id'])
                ->update([
                    'application_number' => $row['application_number'],
                    'funding_type' => $row['funding_type']['funding_type'],
                    'fund_entry_date' => $row['fund_entry_date'],
                    'over_award' => $row['over_award'],
                    'prevented_funding' => $row['prevented_funding'],
                ]);
        }

        foreach ($request->new_rows as $row) {
            CaseFunding::create([
                'incident_id' => $caseFunding->incident_id,
                'application_number' => $row['application_number'],
                'funding_type' => $row['funding_type'],
                'fund_entry_date' => $row['fund_entry_date'],
                'over_award' => $row['over_award'],
                'prevented_funding' => $row['prevented_funding'],
            ]);
        }

        $case = Incident::where('id', $caseFunding->id)->with('funds.fundingType', 'institution')->first();
        $funds = FundingType::get();
        $schools = Institution::get();

        return inertia('Vss::CaseFunding', ['status' => true, 'result' => $case, 'funds' => $funds, 'schools' => $schools, 'now' => date('Y-m-d')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(CaseFunding $caseFunding): RedirectResponse {
        $incident_id = $caseFunding->incident_id;
        $caseFunding->deleted_by_user_id = Auth::user()->user_id;
        $caseFunding->save();

        $caseFunding->delete();

        $case = Incident::where('incident_id', $incident_id)->first();

        return Redirect::route('vss.case-funding.show', [$case->id]);
    }
}
