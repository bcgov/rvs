<?php

namespace Modules\Twp\Http\Controllers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Modules\Twp\Entities\Application;
use Modules\Twp\Entities\Reason;
use Modules\Twp\Http\Requests\ApplicationEditRequest ;
use Modules\Twp\Http\Requests\ApplicationStoreRequest;
use Modules\Yeaf\Entities\Admin;


/**
 *
 */
class ApplicationController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param ApplicationStoreRequest $request
     *
     * @return RedirectResponse
     */
    public function store(ApplicationStoreRequest $request): RedirectResponse {
        $application = Application::create($request->validated());

        return Redirect::route('twp.students.show', [$application->student_id]);
    }

    /**
     * Update the specified resource in storage.
     * @param ApplicationEditRequest $request
     * @param Application $application
     *
     * @return RedirectResponse
     */
    public function update(ApplicationEditRequest $request, Application $application): RedirectResponse {
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
     * Soft delete the application
     * @param Application $application
     *
     * @return RedirectResponse
     */
    public function destroy(Application $application): RedirectResponse {
        // Update Comment column
        $comment = request('comment');
        $application->update([
            'comment' => $comment
        ]);
        // Soft delete application
        $application->delete();
        return redirect()->route('twp.application-list')->with('message', 'Application deleted successfully.');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Application $application
     *
     * @return RedirectResponse
     */
    public function applicationStatus(Request $request, Application $application): RedirectResponse {
        $application = Application::find($application->id);

        if ($request->status != 'DENIED') {
            $application->application_status = $request->status;
            $application->save();
        }

        return Redirect::route('twp.application-list');
    }

    /**
     * @param Request $request
     * @param string $type
     *
     * @return \Illuminate\Contracts\Foundation\Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    public function downloadLetter(Request $request, string $type, mixed $extra): \Illuminate\Contracts\Foundation\Application|ResponseFactory|\Illuminate\Foundation\Application|Response {
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
        $pdf = PDF::loadView($letter_file, compact('admin', 'reasons', 'app', 'now_d', 'contact_email', 'contact_name'))->setPaper('Letter', 'portrait')->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);
        $file_name = $app->student->birth_date;

        $file_name = mt_rand().'-'.$file_name.'-letter.pdf';

        return response($pdf->download($file_name))
            ->header('Content-Disposition', 'attachment; filename="'.$file_name.'"');

    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    public function downloadSchoolLetter(Request $request, mixed $extra): \Illuminate\Contracts\Foundation\Application|ResponseFactory|\Illuminate\Foundation\Application|Response {
        $admin = Admin::first();
        $now_d = date('F d, Y');
        $app = Application::where('id', $extra)->with('student', 'reasons', 'program.institution', 'payments')->first();

        $reasons = Reason::all();
        $contact_name = $request->contact_name;
        $contact_email = $request->contact_email;

        $pdf = PDF::loadView('twp::school-denied', compact('admin', 'reasons', 'app', 'now_d', 'contact_email', 'contact_name'))->setPaper('Letter', 'portrait')->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);
        $file_name = $app->student->birth_date;

        $file_name = mt_rand().'-'.$file_name.'-letter.pdf';

        return response($pdf->download($file_name))
            ->header('Content-Disposition', 'attachment; filename="'.$file_name.'"');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    public function downloadStudentTransferLetter(Request $request, mixed $extra)
    {
        $admin = Admin::first();
        $now_d = date('F d, Y');
        $app = Application::where('id', $extra)->with('student', 'reasons', 'program.institution', 'payments')->first();

        $reasons = Reason::all();
        $contact_name = $request->contact_name;
        $contact_email = $request->contact_email;

        $pdf = PDF::loadView('twp::student-transfer', compact('admin', 'reasons', 'app', 'now_d', 'contact_email', 'contact_name'))->setPaper('Letter', 'portrait')->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);
        $file_name = $app->student->birth_date;

        $file_name = mt_rand().'-'.$file_name.'-letter.pdf';

        return response($pdf->download($file_name))
            ->header('Content-Disposition', 'attachment; filename="'.$file_name.'"');
    }
}
