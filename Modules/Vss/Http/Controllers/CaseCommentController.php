<?php

namespace Modules\Vss\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Response;
use Inertia\ResponseFactory;
use Modules\Vss\Entities\CaseComment;
use Modules\Vss\Entities\Incident;

class CaseCommentController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Inertia\ResponseFactory|\Inertia\Response
     */
    public function show(Incident $caseComment): Response|ResponseFactory {
        $case = Incident::where('id', $caseComment->id)->with('comments', 'institution')->first();
        $staff = User::whereHas('roles', function ($q) {
            return $q->whereIn('name', [Role::VSS_ADMIN, Role::VSS_USER]);
        })->orderBy('created_at', 'desc')->get();

        return inertia('Vss::CaseComment', ['status' => true, 'result' => $case, 'staff' => $staff, 'now' => date('Y-m-d')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Inertia\ResponseFactory|\Inertia\Response
     */
    public function update(Request $request, Incident $caseComment): Response|ResponseFactory {
        $case = Incident::where('id', $caseComment->id)->with('comments', 'institution')->first();
        $staff = User::whereHas('roles', function ($q) {
            return $q->whereIn('name', [Role::VSS_ADMIN, Role::VSS_USER]);
        })->orderBy('created_at', 'desc')->get();

        $current_user_id = Auth::user()->user_id;
        foreach ($request->old_rows as $row) {
            if ($row['staff_user_id'] == $current_user_id) {
                CaseComment::where('id', $row['id'])
                    ->update(['comment_text' => $row['comment_text']]);
            }
        }

        foreach ($request->new_rows as $row) {
            CaseComment::create([
                'incident_id' => $caseComment->incident_id,
                'staff_user_id' => $current_user_id,
                'comment_date' => date('Y-m-d'),
                'comment_text' => trim($row['comment_text']),
            ]);
        }

        return inertia('Vss::CaseComment', ['status' => true, 'result' => $case, 'staff' => $staff, 'now' => date('Y-m-d')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(CaseComment $caseComment): RedirectResponse {
        $incident_id = $caseComment->incident_id;
        $caseComment->deleted_by_user_id = Auth::user()->user_id;
        $caseComment->save();

        $caseComment->delete();

        $case = Incident::where('incident_id', $incident_id)->first();

        return Redirect::route('vss.case-comment.show', [$case->id]);
    }
}
