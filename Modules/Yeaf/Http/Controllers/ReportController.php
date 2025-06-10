<?php

namespace Modules\Yeaf\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Yeaf\Entities\Appeal;
use Modules\Yeaf\Entities\Batch;
use Modules\Yeaf\Entities\Comment;
use Modules\Yeaf\Entities\Grant;
use Modules\Yeaf\Entities\GrantIneligible;
use Modules\Yeaf\Entities\Ineligible;
use Modules\Yeaf\Entities\Institution;
use Modules\Yeaf\Entities\ProgramYear;
use Modules\Yeaf\Entities\Student;
use Response;

class ReportController extends Controller
{

    public function reportsStudents(Request $request): JsonResponse {
        return response()->json(Student::select('student_id', 'first_name', 'last_name', 'sin', 'birth_date',
            'address', 'city', 'province', 'postal_code', 'country', 'tele', 'email', 'gender', 'life',
            'overaward_amount', 'overaward_flag', 'overaward_deducted_amount', 'investigate', 'pen', 'pd',
            'institution_student_number')->get(), 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $type): JsonResponse
    {
        if (Cache::has('traffic_light') && Cache::get('traffic_light') == true) {
            // Traffic light is set and true in the cache
            switch ($type) {
                case 'students': return $this->students($request);
                case 'institutions': return $this->institutions($request);
                case 'grants': return $this->grants($request);
                case 'staff': return $this->staff($request);
                case 'ineligibles': return $this->ineligibles($request);
                case 'grantIneligibles': return $this->grantIneligibles($request);
                case 'comments': return $this->comments($request);
                case 'appeals': return $this->appeals($request);
                case 'programYears': return $this->programYears($request);
                case 'batches': return $this->batches($request);
                case 'studentsWithGrants': return $this->studentsWithGrants($request);
            }
        }

        // Traffic light is not set or is false in the cache
        return response()->json([
            'status' => 'fail',
            'message' => 'Access denied.',
        ]);
    }

    public function students(Request $request): JsonResponse {
        return response()->json(Student::select('student_id', 'first_name', 'last_name', 'sin', 'birth_date',
            'address', 'city', 'province', 'postal_code', 'country', 'tele', 'email', 'gender', 'life',
            'overaward_amount', 'overaward_flag', 'overaward_deducted_amount', 'investigate', 'pen', 'pd',
            'institution_student_number')->get(), 200);
    }

    public function grants(Request $request): JsonResponse {
        return response()->json(Grant::select('grant_id', 'institution_id', 'student_id',
            'program_year_id', 'program_code', 'cheque_batch_number', 'officer_user_id', 'creator_user_id',
            'update_user_id', 'application_number', 'age', 'eligible_need', 'total_award', 'unmet_need',
            'total_bcsl_award', 'total_yeaf_award', 'total_yeaf_award_remit', 'overaward', 'overaward_calc',
            'overaward_deducted_amount', 'reason_for_ineligibility', 'program_name', 'program_other_description',
            'status_code', 'date_issued_month', 'date_issued_year', 'application_type', 'letter_text',
            'custom_pending_reason', 'custom_denial_reason', 'study_period_completion', 'confirmation_bcsl_remission',
            'reassess', 'overaward_cleared', 'withdrawal', 'study_start_date', 'study_end_date',
            'bcsl_remission', 'letter_date', 'cheque_issue_date', 'withdrawal_date', 'status_date', 'last_letter_produced_date',
            'application_receive_date', 'last_evaluation_date')->get());
    }

    public function studentsWithGrants(Request $request): JsonResponse {
        return response()->json(Student::with('grants.grantIneligibles', 'comments', 'grants.appeals', 'grants.py')->get());
    }

    public function staff(Request $request): JsonResponse {
        $users = User::select('user_id', 'first_name', 'last_name', 'disabled', 'tele', 'email')->whereHas('roles', function ($q) {
                $q->whereIn('name', ['YEAF Admin', 'YEAF User']);
            })->get();

        return response()->json($users);
    }

    public function ineligibles(Request $request): JsonResponse {
        return response()->json(Ineligible::select('code_id', 'description', 'active_flag',
            'code_type', 'paragraph_text')->get());
    }

    public function grantIneligibles(Request $request): JsonResponse {
        return response()->json(GrantIneligible::select('grant_id', 'ineligible_code_id',
            'created_by', 'cleared_flag', 'ineligible_code_type')->get());
    }

    public function comments(Request $request): JsonResponse {
        return response()->json(Comment::select('student_id', 'user_id', 'comment_text')->get());
    }

    public function appeals(Request $request): JsonResponse {
        return response()->json(Appeal::select('appeal_id', 'student_id', 'grant_id', 'adjudicated_by_user_id',
            'updated_by_user_id', 'appeal_code', 'appeal_date', 'status_code', 'status_affective_date',
            'other_appeal_explain')->get());
    }

    public function institutions(Request $request): JsonResponse {
        return response()->json(Institution::select('institution_id', 'name', 'address', 'city',
            'province', 'state', 'postal_code', 'zip_code', 'country', 'tele', 'fax')->get());
    }

    public function programYears(Request $request): JsonResponse {
        return response()->json(ProgramYear::select('program_year_id', 'year_start', 'year_end',
            'grant_amount', 'max_years_allowed', 'min_age', 'max_age')->get());
    }

    public function batches(Request $request): JsonResponse {
        return response()->json(Batch::select('batch_number', 'batch_date')->get());
    }

}
