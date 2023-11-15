<?php

namespace Modules\Twp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Modules\Twp\Entities\Application;
use Modules\Twp\Entities\Reason;
use Modules\Twp\Http\Requests\ApplicationEditRequest;
use Modules\Twp\Http\Requests\ApplicationStoreRequest;
use Modules\Yeaf\Entities\Admin;
use PDF;

class ApplicationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  ApplicationEditRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ApplicationStoreRequest $request)
    {
        $application = Application::create($request->validated());

        return Redirect::route('twp.students.show', [$application->student_id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ApplicationEditRequest $request, Application $application)
    {
        Application::where('id', $application->id)->update($request->validated());
        $application = Application::find($application->id);

        $application->reasons()->detach();
        if ($application->application_status == 'DENIED') {
            foreach ($request->reasons as $reason_id) {
                $reason = Reason::find($reason_id);
                $application->reasons()->attach($reason);
            }
        }

        return Redirect::route('twp.students.show', [$application->student_id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function applicationStatus(Request $request, Application $application)
    {
        $application = Application::find($application->id);

        if ($request->status != 'DENIED') {
            $application->application_status = $request->status;
            $application->save();
        }

        return Redirect::route('twp.students.index');
    }

    public function downloadLetter(Request $request, $type, $extra)
    {
        $admin = Admin::first();
        $now_d = date('F d, Y');
        $app = Application::where('id', $extra)->with('student', 'reasons', 'program.institution', 'payments')->first();

        $reasons = Reason::all();
        $contact_name = $request->contact_name;
        $contact_email = $request->contact_email;

        $letter_file = match ($type) {
            'student_success_under_age' => 'twp::student-success-under-age',
            'student_denied' => 'twp::student-denied',
            'school_denied' => 'twp::school-denied',
            default => 'twp::student-success',
        };
        $pdf = PDF::loadView($letter_file, compact('admin', 'reasons', 'app', 'now_d', 'contact_email', 'contact_name'));
        $pdf->getDomPDF()->set_option('enable_php', true);
        $pdf->set_paper('Letter', 'portrait');
        $file_name = $app->student->birth_date;

        $file_name = mt_rand().'-'.$file_name.'-letter.pdf';

        return response($pdf->download($file_name))
            ->header('Content-Disposition', 'attachment; filename="'.$file_name.'"');

    }

    public function downloadSchoolLetter(Request $request, $extra)
    {
        $admin = Admin::first();
        $now_d = date('F d, Y');
        $app = Application::where('id', $extra)->with('student', 'reasons', 'program.institution', 'payments')->first();

        $reasons = Reason::all();
        $contact_name = $request->contact_name;
        $contact_email = $request->contact_email;

        $pdf = PDF::loadView('twp::school-denied', compact('admin', 'reasons', 'app', 'now_d', 'contact_email', 'contact_name'));
        $pdf->getDomPDF()->set_option('enable_php', true);
        $pdf->set_paper('Letter', 'portrait');
        $file_name = $app->student->birth_date;

        $file_name = mt_rand().'-'.$file_name.'-letter.pdf';

        return response($pdf->download($file_name))
            ->header('Content-Disposition', 'attachment; filename="'.$file_name.'"');
    }

    public function downloadStudentTransferLetter(Request $request, $extra)
    {
        $admin = Admin::first();
        $now_d = date('F d, Y');
        $app = Application::where('id', $extra)->with('student', 'reasons', 'program.institution', 'payments')->first();

        $reasons = Reason::all();
        $contact_name = $request->contact_name;
        $contact_email = $request->contact_email;

        $pdf = PDF::loadView('twp::student-transfer', compact('admin', 'reasons', 'app', 'now_d', 'contact_email', 'contact_name'));
        $pdf->getDomPDF()->set_option('enable_php', true);
        $pdf->set_paper('Letter', 'portrait');
        $file_name = $app->student->birth_date;

        $file_name = mt_rand().'-'.$file_name.'-letter.pdf';

        return response($pdf->download($file_name))
            ->header('Content-Disposition', 'attachment; filename="'.$file_name.'"');
    }
}
