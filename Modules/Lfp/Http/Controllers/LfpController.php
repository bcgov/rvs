<?php

namespace Modules\Lfp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Modules\Lfp\Entities\Application;
use Modules\Lfp\Entities\Lfp;
use Response;

class LfpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index($status = true, $newApp = 0)
    {
        $lfps = new Lfp();
        $lfps = $this->paginateLfps($lfps);

        return Inertia::render('Lfp::Applications', ['page' => 'applications', 'status' => $status, 'results' => $lfps, 'app' => $newApp]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Inertia\Response
     */
    public function store(Request $request)
    {
        $sin = $request->input('sin');
        if(empty($sin)){
            return $this->index(false);
        }

        //https://hive.aved.gov.bc.ca/jira/projects/SD/queues/custom/5/SD-52090
        //requires allowing multiple LFPs
//        $lfp = Lfp::with('payments')->where('sin', $sin)->first();
//        if(!is_null($lfp)){
//            return $this->index(true, $lfp->id);
//        }

        $qry = env('LFP_QUERY1').$sin;
        $student = DB::connection('oracle')->select($qry);

        $qry = env('LFP_QUERY2').$sin;
        $applications = DB::connection('oracle')->select($qry);

        if(sizeof($student) > 0 && sizeof($applications) > 0){
            $lfp = new Lfp();
            $lfp->sin = $student->sin;
            $lfp->first_name = $student->first_name;
            $lfp->last_name = $student->last_name;
            $lfp->save();

            return $this->index(true, $lfp->id);
        }

        return $this->index(false, -1);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return \Inertia\Response
     */
    public function show(Lfp $lfp)
    {
        $lfp = Lfp::with('payments', 'applications')->where('id', $lfp->id)->first();

        $qry = env('LFP_QUERY1').$lfp->sin;
        $student = DB::connection('oracle')->select($qry);

        $qry = env('LFP_QUERY2').$lfp->sin;
        $application = DB::connection('oracle')->select($qry);


        return Inertia::render('Lfp::Application', ['status' => true, 'result' => $lfp,
            'student' => $student, 'apps' => $application]);
    }

    /**
     * Connect an SABC app to LFP app.
     * @param Request $request
     * @return Response::json
     */
    public function connectApp(Request $request)
    {
        $app = new Application();
        $app->lfp_id = $request->input('lfp_id');
        $app->application_number = $request->input('application_number');
        $app->save();
        return Response::json(['status' => true]);
    }

    /**
     * Remove an SABC app to LFP app.
     * @param Request $request
     * @return Response::json
     */
    public function removeApp(Request $request)
    {
        Application::where('lfp_id', $request->input('lfp_id'))
            ->where('application_number', $request->input('application_number'))->delete();
        return Response::json(['status' => true]);
    }

    private function paginateLfps($lfps)
    {
        if (request()->filter_fname !== null) {
            $lfps = $lfps->where('first_name', 'ILIKE', '%'.request()->filter_fname.'%');
        }
        if (request()->filter_lname !== null) {
            $lfps = $lfps->where('last_name', 'ILIKE', '%'.request()->filter_lname.'%');
        }

        if (request()->sort !== null) {
            $lfps = $lfps->orderBy(request()->sort, request()->direction);
        } else {
            $lfps = $lfps->orderBy('receive_date', 'desc');
        }

        return $lfps->paginate(25)->onEachSide(1)->appends(request()->query());
    }
}
