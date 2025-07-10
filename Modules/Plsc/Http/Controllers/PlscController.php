<?php

namespace Modules\Plsc\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Plsc\Entities\Application;
use Modules\Plsc\Entities\Util;

class PlscController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param bool $status
     * @param int $newApp
     *
     * @return \Inertia\Response
     */
    public function index($status = true, $newApp = 0): Response {
        $plscs = $this->paginatePlscs();
        $last_sync = Application::select('id', 'sin', 'app_idx', 'created_at')->where('created_at', '!=', null)
            ->orderBy('created_at', 'desc')->first();

        $hours_difference = 2;
        if(!is_null($last_sync)){
            // Calculate the difference in hours
            $hours_difference = Carbon::parse($last_sync->created_at)->diffInHours(Carbon::now());
        }

        // Check if the difference is greater than 1 hour
        if ($hours_difference > 1) {
            // Sync applications
            $this->sync();
            $last_sync = Application::select('id', 'sin', 'app_idx', 'created_at')->where('created_at', '!=', null)
                ->orderBy('created_at', 'desc')->first();

        }
        $last_sync = Carbon::parse($last_sync->created_at)->format('Y-m-d H:i');

        return Inertia::render('Plsc::Applications', ['page' => 'applications', 'lastSync' => $last_sync,
            'status' => $status, 'results' => $plscs, 'app' => $newApp]);
    }

    /**
     * @param bool $status
     * @param int $newApp
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sync(bool $status = true, int $newApp = 0): RedirectResponse {
        $qry = env("PLSC_APP_SYNC") . "0";
        //select last app entered
        $plsc = Application::select('id', 'app_idx', 'sin')->whereNotNull('app_idx')->orderBy('created_at', 'desc')->first();
        if(!is_null($plsc)){
            $qry = env("PLSC_APP_SYNC") . $plsc->app_idx;
        }
        $sfas = DB::connection('oracle')->select($qry);

        foreach ($sfas as $app){
            $check = Application::select('id', 'app_idx', 'sin')->where('app_idx', $app->pl_loan_forgiveness_app_idx)->first();
            if(is_null($check)) {
                $check = Application::firstOrCreate([
                    'sin' => $app->sin,
                    'app_idx' => $app->pl_loan_forgiveness_app_idx,
                ]);
            }
        }

        return Redirect::route('plsc.applications.index');
    }

    /**
     * Show the specified resource.
     * @param Application $plsc
     * @return \Inertia\Response
     */
    public function show(Application $plsc): Response {
        $plsc = Application::where('id', $plsc->id)->first();

        $qry = env('LFP_QUERY1').$plsc->student->sin;
        $student = DB::connection('oracle')->select($qry);

        $application = [];
        if(!is_null($plsc->app_idx)){
            $qry = env('PLSC_SFA_APP').$plsc->app_idx;
            $application = DB::connection('oracle')->select($qry);
        }
        $utils_array = [];
        foreach(Util::where('active_flag', true)->orderBy('field_name', 'asc')->get() as $u){
            $utils_array[$u->field_type][] = $u->field_name;
        }

        return Inertia::render('Plsc::Application', ['status' => true, 'result' => $plsc,
            'student' => $student, 'app' => $application, 'utils' => $utils_array]);
    }

    /**
     * @return mixed
     */
    private function paginatePlscs(): mixed {
        $plscs = Application::where('app_idx', '!=', null);
        if (request()->filter_fname !== null) {
            $plscs = $plscs->where('first_name', 'ILIKE', '%'.request()->filter_fname.'%');
        }

        if (request()->filter_sin !== null) {
            $plscs = $plscs->where('sin', request()->filter_sin);
        }

        if (request()->filter_lname !== null) {
            $plscs = $plscs->where('last_name', 'ILIKE', '%'.request()->filter_lname.'%');
        }
        if (request()->filter_period !== null && request()->filter_period != 'all') {
            switch (request()->filter_period){
                case 'current':
                    // Filter for the current month
                    $plscs = $plscs->whereMonth('created_at', now()->month);
                    break;

                case '3':
                    // Filter for the last 3 months
                    $plscs = $plscs->where('created_at', '>=', Carbon::now()->subMonths(3));
                    break;

                case '6':
                    // Filter for the last 6 months
                    $plscs = $plscs->where('created_at', '>=', Carbon::now()->subMonths(6));
                    break;

                case '12':
                    // Filter for the last 12 months
                    $plscs = $plscs->where('created_at', '>=', Carbon::now()->subMonths(12));
                    break;

                default:
                    break;
            }
        }

        if (request()->sort !== null) {
            $plscs = $plscs->orderBy(request()->sort, request()->direction);
        } else {
            $plscs = $plscs->orderBy('created_at', 'desc');
        }

        return $plscs->paginate(25)->onEachSide(1)->appends(request()->query());
    }
}
