<?php

namespace Modules\Twp\Http\Controllers;

use Inertia\Response;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Modules\Twp\Entities\Application as TwpApp;
use Modules\Twp\Entities\ApplicationReason;
use Modules\Twp\Entities\Grant;
use Modules\Twp\Entities\Institution;
use Modules\Twp\Entities\Payment;
use Modules\Twp\Entities\PaymentType;
use Modules\Twp\Entities\Program;
use Modules\Twp\Entities\Reason;
use Modules\Twp\Entities\Student;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function reportsShow(Request $request): Response
    {
        return Inertia::render('Twp::Maintenance', ['status' => true, 'page' => 'reports']);
    }

    public function switchOn(Request $request): JsonResponse
    {
        // Set traffic light to true
        $traffic_light = true;

        // Store traffic light value in cache for 60 seconds
        Cache::put('twp_traffic_light', $traffic_light, 60 * 60);

        return response()->json([
            'status' => 'success',
            'message' => 'Traffic light set to true',
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function fetchReport(Request $request, string $type): JsonResponse
    {
        if (Cache::has('twp_traffic_light') && Cache::get('twp_traffic_light') == true) {
            // Traffic light is set and true in the cache
            switch ($type) {
                case 'students':
                    return $this->students($request);
                case 'applications':
                    return $this->applications($request);
                case 'grants':
                    return $this->grants($request);
                case 'institutions':
                    return $this->institutions($request);
                case 'payments':
                    return $this->payments($request);
                case 'payment_types':
                    return $this->payment_types($request);
                case 'programs':
                    return $this->programs($request);
                case 'reasons':
                    return $this->reasons($request);
                case 'application_reasons':
                    return $this->applicationReasons($request);
                case 'staff':
                    return $this->staff($request);
            }
        }

        // Traffic light is not set or is false in the cache
        return response()->json([
            'status' => 'fail',
            'message' => 'Access denied.',
        ]);
    }

    public function students(Request $request): JsonResponse {
        return response()->json(Student::select('id', 'first_name', 'last_name', 'alias_name', 'sin', 'birth_date',
            'address', 'pen', 'email', 'gender', 'citizenship', 'bc_resident', 'comment')
            ->with('indigeneity')
            ->whereNull('deleted_at')
            ->get(), 200);
    }

    public function applications(Request $request): JsonResponse {
        return response()->json(TwpApp::select('id', 'student_id', 'received_date', 'application_status', 'denial_reason', 'exception_comments',
            'institution_student_number', 'apply_twp', 'apply_lfg', 'confirmation_enrolment', 'sabc_app_number',
            'fao_name', 'fao_email', 'fao_phone')->whereNull('deleted_at')->get(), 200);
    }

    public function payments(Request $request): JsonResponse {
        return response()->json(Payment::select('id', 'student_id', 'program_id', 'application_id', 'payment_type_id',
            'payment_date', 'payment_amount', 'created_by', 'updated_by')->whereNull('deleted_at')->get(), 200);
    }

    public function payment_types(Request $request): JsonResponse {
        return response()->json(PaymentType::select('id', 'title')->get(), 200);
    }

    public function grants(Request $request): JsonResponse {
        return response()->json(Grant::select('id', 'student_id', 'received_date', 'grant_status', 'grant_comments', 'grant_amount',
            'application_id')->get());
    }

    public function institutions(Request $request): JsonResponse {
        return response()->json(Institution::select('id', 'name', 'active_flag')->get());
    }

    public function programs(Request $request): JsonResponse {
        return response()->json(Program::select('id', 'student_id', 'study_period_start_date', 'institution_name', 'credential',
            'program_length', 'program_length_type', 'total_estimated_cost', 'student_status', 'comments', 'credential_type',
            'institution_twp_id', 'application_id', 'study_field')->get());
    }

    public function reasons(Request $request): JsonResponse {
        return response()->json(Reason::select('id', 'title', 'reason_status', 'letter_body')->get());
    }

    public function applicationReasons(Request $request): JsonResponse {
        return response()->json(ApplicationReason::get());
    }

    public function staff(Request $request): JsonResponse {
        $users = User::select('id', 'user_id', 'first_name', 'last_name', 'disabled',
            'access_type', 'tele', 'email')->whereHas('roles', function ($q): void {
                $q->whereIn('name', ['TWP Admin', 'TWP User']);
            })->get();

        return response()->json($users);
    }

    /**
     * Display first page after login (dashboard page)
     */
    public function index(Request $request): Response {
        $schools = Institution::orderBy('name', 'asc')->get();

        return Inertia::render('Twp::Reports', ['results' => null, 'schools' => $schools]);
    }

    public function searchReports(Request $request): Response {
        $schools = Institution::orderBy('name', 'asc')->get();
        $results = Institution::where('id', $request->institutionId)->with('programs.student')->first();

        return Inertia::render('Twp::Reports', ['results' => $results, 'schools' => $schools]);
    }

}
