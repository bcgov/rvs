<?php

namespace Modules\Yeaf\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Modules\Yeaf\Entities\Admin;
use Modules\Yeaf\Entities\Appeal;
use Modules\Yeaf\Entities\Grant;
use Modules\Yeaf\Entities\GrantIneligible;
use Modules\Yeaf\Entities\Student;
use Modules\Yeaf\Http\Requests\GrantEditRequest;
use Modules\Yeaf\Http\Requests\GrantStoreRequest;
use PDF;
use Response;

class GrantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $grants = new Grant();
        $grants = $this->paginateGrants($grants);

        return Inertia::render('Yeaf::Grants', ['status' => true, 'results' => $grants]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(GrantStoreRequest $request)
    {
        $grant = Grant::create($request->validated());
        $student = Student::where('student_id', $grant->student_id)->first();

        return Redirect::route('yeaf.students.show', [$student->id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(GrantEditRequest $request, Grant $grant)
    {
        $grant = Grant::find($grant->id);
        $grant->institution_id = $request->institution_id;
        $grant->program_name = $request->program_name;
        $grant->program_other_description = $request->program_other_description;
        $grant->application_receive_date = $request->application_receive_date;
        $grant->program_code = $request->program_code;
        $grant->program_year_id = $request->program_year_id;
        $grant->officer_user_id = $request->officer_user_id;
        $grant->study_start_date = $request->study_start_date;
        $grant->study_end_date = $request->study_end_date;
        $grant->age = $request->age;
        $grant->application_number = $request->application_number;
        $grant->application_type = $request->application_type;
        $grant->status_code = $request->status_code;
        $grant->last_evaluation_date = $request->last_evaluation_date;

        if ($request->status_code === 'A' && $request->total_yeaf_award > 0) {
            $grant->total_yeaf_award = $request->total_yeaf_award;
            $grant->cheque_batch_number = $request->cheque_batch_number;
            $grant->cheque_issue_date = $request->cheque_issue_date;
            $grant->withdrawal = $request->withdrawal;
            $grant->withdrawal_date = $request->withdrawal_date;
            $grant->overaward = $request->overaward;
            $grant->overaward_deducted_amount = $request->overaward_deducted_amount;
        }

        $grant->custom_pending_reason = $request->input('custom_pending_reason');
        $grant->custom_denial_reason = $request->input('custom_denial_reason');
        $grant->save();

        return Grant::find($grant->id);
    }

    /**
     * validate to export or to show errors on letter export request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validateLetter(Request $request, Grant $grant)
    {
        $msg = '';
        $stDocname = null;

        if ($grant->study_period_completion) {
            $stDocname = 'rptLtrSuccessComp';
        } elseif ($grant->status_code === 'A') {
            if ($grant->total_yeaf_award > 0) {
                if ($grant->application_type == 'SFAS Extract') {
                    $stDocname = 'rptLtrApprovedSFASExtract';
                } else {
                    $stDocname = 'rptLtrApproved';
                }
            } else {
                $msg = "Status is 'Approved' but no award has been given.";
            }
        } else {
            $countReasons = GrantIneligible::where('grant_id', $grant->grant_id)
                ->where('cleared_flag', false)
                ->where('ineligible_code_type', $grant->status_code)
                ->count();
            if ($grant->status_code == 'P') {
                if (is_null($grant->custom_pending_reason) && $countReasons == 0) {
                    $msg = 'There are no pending reasons to include in the letter.  ';
                } else {
                    $stDocname = 'rptLtrPending';
                }
            } elseif ($grant->status_code == 'D') {
                if (is_null($grant->custom_denial_reason) && $countReasons == 0) {
                    $msg = 'There are no denied reasons to include in the letter.  ';
                } else {
                    $stDocname = 'rptLtrDenied';
                }
            }
        }

        return Response::json(['status' => true, 'msg' => $msg, 'docName' => $stDocname]);
    }

    /**
     * validate to export or to show errors on letter export request.
     *
     * @param  null  $docName
     * @return \Illuminate\Http\RedirectResponse
     */
    public function exportLetter(Grant $grant, $docName = null)
    {
        if (! is_null($docName)) {
            $doc = $docName;
            $admin = Admin::first();
            $student = $grant->student;
            $officer = $grant->officer;
            $now_d = date('F d, Y');
            $now_t = date('H:m:i');
            $user = Auth::user();
            $pdf = PDF::loadView('yeaf::pdf', compact('grant', 'admin', 'user', 'doc', 'student', 'officer', 'now_d', 'now_t'));

            $file_name = $student->first_name.'-'.$student->last_name.'-'.match ($grant->status_code) {
                'A' => 'approval-letter',
                'D' => 'denial-letter',
                'P' => 'pending-letter',
            };

            return $pdf->download($file_name.'.pdf');
        }
    }

    /**
     * validate to export or to show errors on letter export request.
     *
     * @param  null  $docName
     * @return \Illuminate\Http\RedirectResponse
     */
    public function exportWithdrawalLetter(Grant $grant, $docName = null)
    {
        if (! is_null($docName)) {
            $doc = $docName;
            $admin = Admin::first();
            $student = $grant->student;
            $officer = $grant->officer;
            $now_d = date('F d, Y');
            $now_t = date('H:m:i');
            $user = Auth::user();
            $pdf = PDF::loadView('yeaf::pdf', compact('grant', 'admin', 'user', 'doc', 'student', 'officer', 'now_d', 'now_t'));

            $file_name = $student->first_name.'-'.$student->last_name.'-withdrawal-letter';

            return $pdf->download($file_name.'.pdf');
        }
    }

    /**
     * update and evaluate a grant.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function evaluateApp(GrantEditRequest $request, Grant $grant)
    {
        $grant = $this->update($request, $grant);
        $this->updatePendingIneligibles($grant, $request);
        $this->updateDeniedIneligibles($grant, $request);
        $this->updateAppeals($grant, $request);

        $this->clearFlags($request, $grant);

        $overall_app_ineligible = false;
        $overall_messages = [];
        [$msg, $app_ineligible, $appeal_status, $age] = $this->checkAge(true, true, $grant);
        $grant->age = $age;
        $grant->save();
        $grant = Grant::find($grant->id);

        $return_grant = Grant::where('id', $grant->id)->with('school', 'grantPendingIneligibles', 'grantDeniedIneligibles', 'appeals')->first();
        $overall_app_ineligible = $this->getOverallMsgAndIneligible($msg, $app_ineligible, $overall_messages, $overall_app_ineligible);

        [$msg, $app_ineligible] = $this->checkMaxYears(true, true, $grant, $msg, $app_ineligible);
        $overall_app_ineligible = $this->getOverallMsgAndIneligible($msg, $app_ineligible, $overall_messages, $overall_app_ineligible);

        [$msg, $app_ineligible] = $this->datewatch(true, true, $grant, $msg, $app_ineligible);
        $overall_app_ineligible = $this->getOverallMsgAndIneligible($msg, $app_ineligible, $overall_messages, $overall_app_ineligible);

        [$msg, $app_ineligible] = $this->checkProgramYear(true, true, $grant, $msg, $app_ineligible);
        $overall_app_ineligible = $this->getOverallMsgAndIneligible($msg, $app_ineligible, $overall_messages, $overall_app_ineligible);

        $grant = Grant::find($grant->id);
        $msg = $this->setStatus($grant);
        if ($msg != '') {
            if (! is_null($msg)) {
                $overall_messages[] = $msg;
            }
        }
        $grant = Grant::find($grant->id);
        $return_grant = Grant::where('id', $grant->id)->with('school', 'grantPendingIneligibles', 'grantDeniedIneligibles', 'appeals')->first();

        return Response::json(['status' => true, 'msg' => $overall_messages, 'app_ineligible' => $overall_app_ineligible,
            'appeal_status' => $appeal_status, 'grant' => $return_grant, ]);
    }

    private function getOverallMsgAndIneligible($msg, $app_ineligible, &$overall_messages, $overall_app_ineligible)
    {
        if ($msg != '' || $app_ineligible == true) {
            if (! is_null($msg) && ! in_array($msg, $overall_messages)) {
                $overall_messages[] = $msg;
            }
            if ($app_ineligible == true) {
                $overall_app_ineligible = true;
            }
        }

        return $overall_app_ineligible;
    }

    private function updatePendingIneligibles(Grant $grant, $request)
    {
        //update existing records
        if (isset($request->grant_pending_ineligibles)) {
            foreach ($request->grant_pending_ineligibles as $pending) {
                $grant_ineligible = GrantIneligible::find($pending->id);
                $grant_ineligible->ineligible_code_id = $pending->ineligible_code_id;
                $grant_ineligible->cleared_flag = $pending->cleared_flag;
                $grant_ineligible->save();
            }
        }

        //add new records
        if (isset($request->new_pending_reasons)) {
            foreach ($request->new_pending_reasons as $pending) {
                if ($pending->ineligible_code_id != 0) {
                    $check = GrantIneligible::where([
                        'grant_id' => $grant->grant_id,
                        'ineligible_code_id' => $pending->ineligible_code_id,
                        'ineligible_code_type' => 'P',
                    ])->first();
                    if (is_null($check)) {
                        $grant_ineligible = new GrantIneligible();
                        $grant_ineligible->grant_id = $grant->grant_id;
                        $grant_ineligible->ineligible_code_id = $pending->ineligible_code_id;
                        $grant_ineligible->ineligible_code_type = 'P';
                        $grant_ineligible->cleared_flag = $pending->cleared_flag;
                        $grant_ineligible->created_by = Str::upper(Auth::user()->user_id);
                        $grant_ineligible->save();
                    }
                }
            }
        }

        return null;
    }

    private function updateDeniedIneligibles(Grant $grant, $request)
    {
        //update existing records
        if (isset($request->grant_denied_ineligibles)) {
            foreach ($request->grant_denied_ineligibles as $denied) {
                $grant_ineligible = GrantIneligible::find($denied->id);
                $grant_ineligible->ineligible_code_id = $denied->ineligible_code_id;
                $grant_ineligible->cleared_flag = $denied->cleared_flag;
                $grant_ineligible->save();
            }
        }

        //add new records
        if (isset($request->new_denial_reasons)) {
            foreach ($request->new_denial_reasons as $denied) {
                if ($denied->ineligible_code_id != 0) {
                    $check = GrantIneligible::where(['grant_id' => $grant->grant_id, 'ineligible_code_id' => $denied->ineligible_code_id,
                        'ineligible_code_type' => 'D', ])->first();
                    if (is_null($check)) {
                        $grant_ineligible = new GrantIneligible();
                        $grant_ineligible->grant_id = $grant->grant_id;
                        $grant_ineligible->ineligible_code_id = $denied->ineligible_code_id;
                        $grant_ineligible->ineligible_code_type = 'D';
                        $grant_ineligible->cleared_flag = $denied->cleared_flag;
                        $grant_ineligible->created_by = Str::upper(Auth::user()->user_id);
                        $grant_ineligible->save();
                    }
                }
            }
        }

        return null;
    }

    private function updateAppeals(Grant $grant, $request)
    {
        //update existing records
        if (isset($request->appeals)) {
            foreach ($request->appeals as $appeal) {
                $grant_appeal = Appeal::find($appeal->id);
                $grant_appeal->adjudicated_by_user_id = $appeal->adjudicated_by_user_id;
                $grant_appeal->updated_by_user_id = Str::upper(Auth::user()->user_id);
                $grant_appeal->appeal_code = $appeal->appeal_code;
                $grant_appeal->appeal_date = $appeal->appeal_date;
                $grant_appeal->status_code = $appeal->status_code;
                $grant_appeal->status_affective_date = $appeal->status_affective_date;
                $grant_appeal->other_appeal_explain = $appeal->input('other_appeal_explain');
                $grant_appeal->save();
            }
        }

        //add new records
        if (isset($request->new_appeals)) {
            foreach ($request->new_appeals as $appeal) {
                if ($appeal->appeal_code != 0) {
                    $last_appeal_id = Appeal::orderByDesc('appeal_id')->first();
                    $grant_appeal = new Appeal();
                    $grant_appeal->grant_id = $grant->grant_id;
                    $grant_appeal->appeal_id = $last_appeal_id->appeal_id + 1;
                    $grant_appeal->student_id = $grant->student_id;
                    $grant_appeal->program_year_id = $grant->program_year_id;
                    $grant_appeal->adjudicated_by_user_id = $appeal->adjudicated_by_user_id;
                    $grant_appeal->updated_by_user_id = Str::upper(Auth::user()->user_id);
                    $grant_appeal->appeal_code = $appeal->appeal_code;
                    $grant_appeal->appeal_date = $appeal->appeal_date;
                    $grant_appeal->status_code = $appeal->status_code;
                    $grant_appeal->status_affective_date = $appeal->status_affective_date;
                    $grant_appeal->other_appeal_explain = $appeal->input('other_appeal_explain');
                    $grant_appeal->save();
                }
            }
        }

        return null;
    }

    private function setStatus(Grant $grant)
    {
        $record = Grant::select('grant_id')->withCount(['grantIneligibles as PendingCnt' => function ($query) {
            $query->where('ineligible_code_type', 'P')->where('cleared_flag', false);
        }], 'ineligible_code_type')->withCount(['grantIneligibles as DeniedCnt' => function ($query) {
            $query->where('ineligible_code_type', 'D')->where('cleared_flag', false);
        }], 'id')->where('grant_id', $grant->grant_id)->groupBy('grant_id')->orderBy('grant_id', 'ASC')->first();

        $status = 'P';
        $msg = '';
        if (! is_null($record)) {
            if ($record->DeniedCnt > 0) {
                $status = 'D';
                $msg = 'Application Status set to Denied. Some denial reasons has not been cleared.';
            } elseif ($record->PendingCnt > 0) {
                $status = 'P';
                $msg = 'Application Status set to Pending. Some Pending reasons has not been cleared.';
            } else {
                $status = is_null($grant->status_code) ? 'P' : $grant->status_code;
            }
        }

        if ($status != '' && $status != $grant->status_code) {
            $grant->status_code = $status;
            $grant->status_date = date('Y-m-d', strtotime('now'));
            $grant->save();
        }

        return $msg;
    }

    private function checkProgramYear($messageFlag, $createIneligibleFlag, Grant $grant, $msg = '', $app_ineligible = false)
    {
        //if existing app start date is greater than this end date or existing app end date is less than this start date,
        //then this app is okay, otherwise it overlaps an existing app
        $same_program_year = Grant::where('student_id', $grant->student_id)
            ->where('total_yeaf_award', '>', 0)
            ->where('grant_id', '!=', $grant->grant_id)
            ->where('program_year_id', $grant->py->program_year_id)
            ->get();
        if ($same_program_year->count() > 0) {
            if ($messageFlag) {
                $msg = 'There are other applications for this person with an award for the same program year.';
            }
            if ($createIneligibleFlag) {
                $this->addIneligibleReason($grant, '11');
                $app_ineligible = true;
            }
        }

        return [$msg, $app_ineligible];
    }

    private function datewatch($messageFlag, $createIneligibleFlag, Grant $grant, $msg = '', $app_ineligible = false)
    {
        $date1 = '';
        $date2 = '';
        $dateprogram = 0;
        if (! is_null($grant->study_start_date) && ! is_null($grant->study_end_date)) {
            $date1 = new Carbon($grant->study_start_date);
            $date2 = new carbon($grant->study_end_date);

            $dateprogram = $date2->diffInDays($date1);
            if ($dateprogram < 41) {
                if ($messageFlag) {
                    $msg = 'The end date is shorter than 6 weeks. <br/>This application does not meet the course length criteria.<br/>Program Length Error.';
                }
                if ($createIneligibleFlag) {
                    $this->addIneligibleReason($grant, '05');
                    $app_ineligible = true;
                }
            }

            //if existing app start date is greater than this end date or existing app end date is less than this start date
            //then this app is okay, otherwise it overlaps an existing app
            $same_program_period = Grant::where('student_id', $grant->student_id)
                ->where('study_start_date', '<=', date('Y-m-d', strtotime($grant->study_end_date)))
                ->where('study_end_date', '>=', date('Y-m-d', strtotime($grant->study_start_date)))
                ->where('grant_id', '!=', $grant->grant_id)
                ->where('total_yeaf_award', '>', 0)
                ->get();
            if ($same_program_period->count() > 0) {
                if ($messageFlag) {
                    $msg = 'There are other applications with overlapping program periods.';
                }
                if ($createIneligibleFlag) {
                    $this->addIneligibleReason($grant, '10');
                    $app_ineligible = true;
                }
            }
        }

        return [$msg, $app_ineligible];
    }

    private function checkMaxYears($messageFlag, $createIneligibleFlag, Grant $grant, $msg = '', $app_ineligible = false)
    {
        $results = Student::select('program_year_id')
            ->join('grants', 'students.student_id', '=', 'grants.student_id')
            ->where('total_yeaf_award', '>', 0)
            ->where('grant_id', '<>', $grant->grant_id)
            ->where('grants.student_id', $grant->student->student_id)
            ->groupBy('program_year_id')
            ->get();

        if (! is_null($results) && count($results) >= $grant->py->max_years_allowed) {
            if ($messageFlag) {
                $msg = 'This student has received funding for '.count($results).'.';
            }
            if ($createIneligibleFlag) {
                $this->addIneligibleReason($grant, '09');
                $app_ineligible = true;
            }
        }
        $grant_ineligible_check = GrantIneligible::where('grant_id', $grant->grant_id)
            ->where('ineligible_code_id', '09')
            ->where('cleared_flag', true)
            ->first();
        if (! is_null($grant_ineligible_check)) {
            $msg = '';
            $app_ineligible = false;
        }

        return [$msg, $app_ineligible];
    }

    private function clearFlags(Request $request, Grant $grant)
    {
        $grantIneligibles = GrantIneligible::where('grant_id', $grant->id)->where('created_by', 'ilike', 'SYS:%')->get();
        foreach ($grantIneligibles as $g) {
            $g->cleared_flag = true;
            $g->save();
        }
    }

    private function checkAge($messageFlag, $createIneligibleFlag, Grant $grant)
    {
        $age_within_range = null;
        $appeal_status = null;
        $app_ineligible = false;
        $age = $this->ageCalc($grant);
        $msg = '';

        //now check to see if there is an age related appeal
        if ($age > $grant->py->max_age) {
            $age_within_range = 'Over the age of '.$grant->py->max_age;
            $appeal_status = Appeal::where('student_id', $grant->student->student_id)->where('grant_id', $grant->grant_id)
                ->where('appeal_code', 'OA')->orderBy('appeal_date', 'desc')->first();
        } elseif ($age < $grant->py->min_age) {
            $age_within_range = 'Under the age of '.$grant->py->min_age;
            $appeal_status = Appeal::where('student_id', $grant->student->student_id)->where('grant_id', $grant->grant_id)
                ->where('appeal_code', 'UA')->orderBy('appeal_date', 'desc')->first();
        }

        if (! is_null($appeal_status)) {
            $appeal_status = $appeal_status->status_code;
        }

        if ($createIneligibleFlag && ! is_null($age_within_range) && ! is_null($appeal_status) && $appeal_status != 'A') {
            $this->addIneligibleReason($grant, '02');
            $app_ineligible = true;
        }

        if (! is_null($age_within_range)) {
            if (is_null($appeal_status)) {
                $msg = 'Student is '.$age_within_range.' and there is no appeal entered.';
            } elseif ($appeal_status != 'A') {
                $msg = 'Student is '.$age_within_range.' and there is an age appeal with status of ';
                $msg .= match ($appeal_status) {
                    //                    'A' => 'Approved',
                    'P' => 'Pending',
                    'D' => 'Denied',
                };
            }
        }

        return [$msg, $app_ineligible, $appeal_status, $age];
    }

    private function ageCalc(Grant $grant)
    {
        $yDate = new DateTime(date('Ymd', strtotime('now')));
        if (! is_null($grant->study_start_date)) {
            $yDate = new DateTime(date('Ymd', strtotime($grant->study_start_date)));
        }
        $birth_date_y = new DateTime(date('Ymd', strtotime($grant->student->birth_date)));
        $interval = $birth_date_y->diff($yDate);

        return $interval->y;

    }

    private function addIneligibleReason(Grant $grant, $ineligible_code_id)
    {
        $grant_ineligibles = GrantIneligible::where('grant_id', $grant->grant_id)
            ->where('ineligible_code_id', $ineligible_code_id)
            ->first();
        if (is_null($grant_ineligibles)) {
            $this->createGrantIneligible($grant, $ineligible_code_id, 'D');
        }
    }

    private function createGrantIneligible(Grant $grant, $ineligible_code_id, $ineligible_code_type)
    {
        $check = GrantIneligible::where(['grant_id' => $grant->grant_id, 'ineligible_code_id' => $ineligible_code_id,
            'ineligible_code_type' => $ineligible_code_type, ])->first();

        if (is_null($check)) {
            $grant_ineligible = new GrantIneligible();
            $grant_ineligible->grant_id = $grant->grant_id;
            $grant_ineligible->ineligible_code_id = $ineligible_code_id;
            $grant_ineligible->created_by = 'SYS:'.Auth::user()->user_id;
            $grant_ineligible->cleared_flag = false;
            $grant_ineligible->ineligible_code_type = $ineligible_code_type;
            $grant_ineligible->save();
        }
    }
}
