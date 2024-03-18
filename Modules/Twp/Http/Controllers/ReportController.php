<?php

namespace Modules\Twp\Http\Controllers;

use App\Models\User;
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
use Response;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function reportsShow(Request $request): \Inertia\Response
    {
        return Inertia::render('Twp::Maintenance', ['status' => true, 'page' => 'reports']);
    }

    public function switchOn(Request $request)
    {
        // Set traffic light to true
        $traffic_light = true;

        // Store traffic light value in cache for 60 seconds
        Cache::put('twp_traffic_light', $traffic_light, 60);

        return Response::json([
            'status' => 'success',
            'message' => 'Traffic light set to true',
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function fetchReport(Request $request, $type): \Illuminate\Http\JsonResponse
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

    public function students(Request $request)
    {
        return response()->json(Student::select('id', 'first_name', 'last_name', 'sin', 'birth_date',
            'address', 'pen', 'email', 'gender', 'citizenship', 'bc_resident', 'comment')
            ->with('indigeneity')
            ->get(), 200);
    }

    public function applications(Request $request)
    {
        return response()->json(TwpApp::select('id', 'student_id', 'received_date', 'application_status', 'denial_reason', 'exception_comments',
            'institution_student_number', 'apply_twp', 'apply_lfg', 'confirmation_enrolment', 'sabc_app_number',
            'fao_name', 'fao_email', 'fao_phone')->get(), 200);
    }

    public function payments(Request $request)
    {
        return response()->json(Payment::select('id', 'student_id', 'program_id', 'application_id', 'payment_type_id',
            'payment_date', 'payment_amount', 'created_by', 'updated_by')->get(), 200);
    }

    public function payment_types(Request $request)
    {
        return response()->json(PaymentType::select('id', 'title')->get(), 200);
    }

    public function grants(Request $request)
    {
        return response()->json(Grant::select('id', 'student_id', 'received_date', 'grant_status', 'grant_comments', 'grant_amount',
            'application_id')->get());
    }

    public function institutions(Request $request)
    {
        return response()->json(Institution::select('id', 'name', 'active_flag')->get());
    }

    public function programs(Request $request)
    {
        return response()->json(Program::select('id', 'student_id', 'study_period_start_date', 'institution_name', 'credential',
            'program_length', 'program_length_type', 'total_estimated_cost', 'student_status', 'comments', 'credential_type',
            'institution_twp_id', 'application_id', 'study_field')->get());
    }

    public function reasons(Request $request)
    {
        return response()->json(Reason::select('id', 'title', 'reason_status', 'letter_body')->get());
    }

    public function applicationReasons(Request $request)
    {
        return response()->json(ApplicationReason::get());
    }

    public function staff(Request $request)
    {
        $users = User::select('id', 'user_id', 'first_name', 'last_name', 'disabled',
            'access_type', 'tele', 'email')->whereHas('roles', function ($q) {
                $q->whereIn('name', ['TWP Admin', 'TWP User']);
            })->get();

        return response()->json($users);
    }

    /**
     * Display first page after login (dashboard page)
     */
    public function index(Request $request)
    {
        $schools = Institution::orderBy('name', 'asc')->get();

        return Inertia::render('Twp::Reports', ['results' => null, 'schools' => $schools]);
    }

    public function downloadSingleStudentReport(Request $request, $case = null)
    {
        //        $case = Incident::where('id', $case->id)->with(
        //            'primaryAudit', 'referral',
        //            'funds.fundingType', 'comments', 'institution', 'audits', 'offences.offence', 'sanctions')->first();
        //        $now_d = date('Y-m-d');
        //        $now_t = date('H:m:i');
        //        $pdf = PDF::loadView('pdf', compact('case', 'now_d', 'now_t'));
        //
        //        return $pdf->download(mt_rand().'-'.$case->incident_id.'-student_report.pdf');
    }

    public function searchReports(Request $request)
    {
        $schools = Institution::orderBy('name', 'asc')->get();
        $results = Institution::where('id', $request->institutionId)->with('programs.student')->first();

        return Inertia::render('Twp::Reports', ['results' => $results, 'schools' => $schools]);
    }

    //    private function fetchReport(Request $request)
    //    {
    //        $table = [];
    //        $funding_types = FundingType::orderBy('funding_type')->get();
    //        $areas_of_audit = AreaOfAudit::orderBy('description')->get();
    //        foreach ($areas_of_audit as $area) {
    //            $table[$area->description] = [];
    //            $table[$area->description]['TOTAL'] = 0;
    //            foreach ($funding_types as $type) {
    //                $table[$area->description][$type->funding_type] = 0;
    //            }
    //            asort($table[$area->description]);
    //        }
    //        $pre_audit_table = $table;
    //        $post_audit_table = $table;
    //        $total_audit_table = $table;
    //
    //        $start_date_range = date('Y-m-d', strtotime('6 months ago'));
    //        $end_date_range = date('Y-m-d');
    //        if ($request->inputStartDate) {
    //            $start_date_range = date('Y-m-d', strtotime($request->inputStartDate));
    //        }
    //        if ($request->inputEndDate) {
    //            $end_date_range = date('Y-m-d', strtotime($request->inputEndDate));
    //        }
    //
    //        $funds = CaseFunding::with('incident.primaryAudit');
    //        if ($request->type == 'overaward') {
    //            $funds = $funds->where('over_award', '>', 0);
    //        } else {
    //            $funds = $funds->where('prevented_funding', '>', 0);
    //        }
    //
    //        $funds = $funds->where('fund_entry_date', '>=', $start_date_range)
    //            ->where('fund_entry_date', '<=', $end_date_range)
    //            ->get();
    //
    //        foreach ($funds as $fund) {
    //            if ($fund->incident->audit_type == 'P') {
    //                $post_audit_table[$fund->incident->primaryAudit->description][$fund->funding_type] += floatval($fund->over_award);
    //                $post_audit_table[$fund->incident->primaryAudit->description]['TOTAL'] += floatval($fund->over_award);
    //            } else {
    //                $pre_audit_table[$fund->incident->primaryAudit->description][$fund->funding_type] += floatval($fund->over_award);
    //                $pre_audit_table[$fund->incident->primaryAudit->description]['TOTAL'] += floatval($fund->over_award);
    //            }
    //            $total_audit_table[$fund->incident->primaryAudit->description][$fund->funding_type] += floatval($fund->over_award);
    //            $total_audit_table[$fund->incident->primaryAudit->description]['TOTAL'] += floatval($fund->over_award);
    //        }
    //
    //        return [$pre_audit_table, $post_audit_table, $total_audit_table];
    //    }
}
