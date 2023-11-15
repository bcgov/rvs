<?php

namespace Modules\Vss\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Vss\Entities\AreaOfAudit;
use Modules\Vss\Entities\CaseFunding;
use Modules\Vss\Entities\FundingType;
use Modules\Vss\Entities\Incident;
use PDF;

class ReportController extends Controller
{
    public function downloadSingleStudentReport(Request $request, Incident $case)
    {
        $case = Incident::where('id', $case->id)->with(
            'primaryAudit', 'referral',
            'funds.fundingType', 'comments', 'institution', 'audits', 'offences.offence', 'sanctions')->first();
        $now_d = date('Y-m-d');
        $now_t = date('H:m:i');
        $pdf = PDF::loadView('vss.pdf', compact('case', 'now_d', 'now_t'));

        return $pdf->download(mt_rand().'-'.$case->incident_id.'-student_report.pdf');
    }

    public function searchReports(Request $request)
    {
        [$results['pre'], $results['post'], $results['total']] = $this->fetchReport($request);

        return Inertia::render('Vss::Reports', ['results' => $results, 'start' => $request->inputStartDate, 'end' => $request->inputEndDate]);
    }

    private function fetchReport(Request $request)
    {
        $table = [];
        $funding_types = FundingType::orderBy('funding_type')->get();
        $areas_of_audit = AreaOfAudit::orderBy('description')->get();
        foreach ($areas_of_audit as $area) {
            $table[$area->description] = [];
            $table[$area->description]['TOTAL'] = 0;
            foreach ($funding_types as $type) {
                $table[$area->description][$type->funding_type] = 0;
            }
            asort($table[$area->description]);
        }
        $pre_audit_table = $table;
        $post_audit_table = $table;
        $total_audit_table = $table;

        $start_date_range = date('Y-m-d', strtotime('6 months ago'));
        $end_date_range = date('Y-m-d');
        if ($request->inputStartDate) {
            $start_date_range = date('Y-m-d', strtotime($request->inputStartDate));
        }
        if ($request->inputEndDate) {
            $end_date_range = date('Y-m-d', strtotime($request->inputEndDate));
        }

        $funds = CaseFunding::with('incident.primaryAudit');
        if ($request->type == 'overaward') {
            $funds = $funds->where('over_award', '>', 0);
        } else {
            $funds = $funds->where('prevented_funding', '>', 0);
        }

        $funds = $funds->where('fund_entry_date', '>=', $start_date_range)
            ->where('fund_entry_date', '<=', $end_date_range)
            ->get();

        foreach ($funds as $fund) {
            if ($fund->incident->audit_type == 'P') {
                $post_audit_table[$fund->incident->primaryAudit->description][$fund->funding_type] += floatval($fund->over_award);
                $post_audit_table[$fund->incident->primaryAudit->description]['TOTAL'] += floatval($fund->over_award);
            } else {
                $pre_audit_table[$fund->incident->primaryAudit->description][$fund->funding_type] += floatval($fund->over_award);
                $pre_audit_table[$fund->incident->primaryAudit->description]['TOTAL'] += floatval($fund->over_award);
            }
            $total_audit_table[$fund->incident->primaryAudit->description][$fund->funding_type] += floatval($fund->over_award);
            $total_audit_table[$fund->incident->primaryAudit->description]['TOTAL'] += floatval($fund->over_award);
        }

        return [$pre_audit_table, $post_audit_table, $total_audit_table];
    }
}
