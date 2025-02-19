<?php

namespace Modules\Neb\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Inertia\Inertia;
use Modules\Neb\Entities\Application;
use Modules\Neb\Entities\BursaryPeriod;
use Modules\Neb\Entities\ElPotential;
use Modules\Neb\Entities\ElPotentialRestriction;
use Modules\Neb\Entities\ElPotentialRestrictionDetail;
use Modules\Neb\Entities\ElSinPySsd;
use Modules\Neb\Entities\Neb;
use Modules\Neb\Entities\Restriction;
use Modules\Neb\Entities\SfasProgram;
use Modules\Neb\Entities\Student;

class NebController extends Controller
{
    public $programCodesString;

    public $formattedBpsd;

    public $formattedBped;

    public function exportNeb(Request $request, $type, $id)
    {
        $bursaryPeriod = BursaryPeriod::find($id);
        if ($bursaryPeriod == null) {
            return null;
        }

        try {
            $type = match ($type) {
                'el' => 'eligible',
                'in' => 'ineligible',
                'aw' => 'awarded',
                'aw_txt' => 'awarded_text',
            };

            //default to exporting awarded
            $potentials = ElPotential::where('bursary_period_id', $bursaryPeriod->id)->where('award_or_deny', 'Award')
                ->orderBy('weekly_unmet_need', 'desc')
                ->orderBy('total_unmet_need', 'desc')->get();

            switch ($type) {
                case 'eligible':
                    $potentials = ElPotential::where('bursary_period_id', $bursaryPeriod->id)->where('eligibility', 'Eligible')->get();
                    break;

                case 'ineligible':
                    $potentials = ElPotential::where('bursary_period_id', $bursaryPeriod->id)->where('eligibility', 'Ineligible')->get();
                    break;

                case 'awarded_text':
                    $txtRows = '';
                    foreach ($potentials as $potential) {
                        $txtRows .= $this->awardedTextLine($potential, $bursaryPeriod)."\n";
                    }

                    $filename = $type.'-'.$bursaryPeriod->bursary_period_start_date.'TO'.$bursaryPeriod->bursary_period_end_date.'.txt';
                    $headers = [
                        'Content-Disposition' => 'attachment; filename='.$filename,
                        'Content-Type' => 'text/plain',
                    ];

                    return Response::streamDownload(function () use ($txtRows) {
                        echo $txtRows;
                    }, $filename, $headers);

            }

            $csvContent = "ApplicationNum,SIN,PostalCode,BirthDate,FirstName,MiddleName,LastName,AssessedNeedAmount,TotalUnmetNeed,WeeksOfStudy,WeeklyUnmetNeed,ProgramYear,StreetAddress1,StreetAddress2,City,Province,Gender,PhoneNumber,StudyStartDate,StudyEndDate,InstitutionName,ProgramCode,InstCode,AreaOfStudy,DegreeLevel,BursaryPeriodId,MonthOverlap,NumDayOverlap,ValidInstitution,Restriction,AwardedInPriorYear,Withdrawal,NurseType,Sector,Eligibility,NebIneligibleReason,RankByUnmetNeed,RankByNurseType,RankBySector,AwardOrDeny,NebDenyReason,AwardAmount,SfasAwardId,SupplierNo\n"; // CSV header row
            // Prepare the CSV content
            foreach ($potentials as $potential) {
                $csvContent .= $this->prepareCsvLine($potential)."\n";
            }

            $filename = $type.'-'.$bursaryPeriod->bursary_period_start_date.'TO'.$bursaryPeriod->bursary_period_end_date.'.csv';
            // Set response headers to indicate a downloadable CSV file
            $headers = [
                'Content-Disposition' => 'attachment; filename='.$filename,
                'Content-Type' => 'text/csv',
            ];

            return Response::streamDownload(function () use ($csvContent) {
                echo $csvContent;
            }, $filename, $headers);

        } catch (\Exception $exception) {
            Log::error('Error generating CSV for download: '.$exception);

            return Response::make('Internal server error.', 500, []);
        }
    }

    public function fetchNeb(Request $request)
    {
        $bpId = $request->id ?? $request->input('id');
        $sortBy = $request->input('sort_by') ?? 'id';
        $sortDir = $request->input('sort_dir') ?? 'asc';

        $page = $request->input('page', 1);
        $bursaryPeriod = BursaryPeriod::find($bpId);
        if ($bursaryPeriod == null) {
            return null;
        }

        //fetch stats only on the first page
        $el = ElPotential::where('bursary_period_id', $bursaryPeriod->id)->where('eligibility', 'Eligible');
        $inEl = ElPotential::where('bursary_period_id', $bursaryPeriod->id)->where('eligibility', 'Ineligible');
        $award = ElPotential::where('bursary_period_id', $bursaryPeriod->id)->where('award_or_deny', 'Award');
        $deny = ElPotential::where('bursary_period_id', $bursaryPeriod->id)->where('award_or_deny', 'Deny');
        $secPub = ElPotential::where('bursary_period_id', $bursaryPeriod->id)->where('sector', 'Public');
        $secPri = ElPotential::where('bursary_period_id', $bursaryPeriod->id)->where('sector', 'Private');
        $stats = [
            'eligible' => $el->count(),
            'ineligible' => $inEl->count(),
            'awarded' => $award->count(),
            'denied' => $deny->count(),
            'sectorPub' => $secPub->count(),
            'sectorPri' => $secPri->count(),
            'info' => $bursaryPeriod,
        ];

        $potentials = ElPotential::where('bursary_period_id', $bursaryPeriod->id)
            ->orderBy($sortBy, $sortDir)->paginate(25);

        return Inertia::render('Neb::BursaryPeriod', ['results' => $potentials,
            'stats' => $stats, 'id' => $bursaryPeriod->id]);
    }

    // FINALIZE FUNCTIONS START HERE

    public function finalizeNeb(Request $request)
    {
        $bursaryPeriod = BursaryPeriod::find($request->input('bursary_period_id'));
        if ($bursaryPeriod == null) {
            return null;
        }
        if ($bursaryPeriod->awarded === true) {
            return Inertia::render('Error', ['status' => 500, 'message' => 'This bursary period has already been awarded.']);
        }
        $this->formattedBpsd = $bursaryPeriod->bursary_period_start_date;
        $this->formattedBped = $bursaryPeriod->bursary_period_end_date;

        $programCodes = SfasProgram::where('eligible', true)->pluck('sfas_program_code');
        $this->programCodesString = $programCodes->map(function ($row) {
            return '\''.$row.'\'';
        })->join(', ');

        switch ($request->input('step')) {
            case 0:
                $this->processStudents($bursaryPeriod);
                break;
            case 1:
                $this->createApplications($bursaryPeriod);
                break;
            case 2:
                $this->cleanup($bursaryPeriod);
                break;

            default:
                break;
        }

        return Redirect::route('neb.bursary-periods.show', [$bursaryPeriod->id]);
    }

    private function processStudents(BursaryPeriod $bP)
    {
        $students = ElPotential::where('bursary_period_id', $bP->id)->get();
        foreach ($students as $student) {
            Student::firstOrCreate([
                'sin' => $student->sin,
                'date_of_birth' => $student->birth_date,
                'first_name' => $student->first_name,
                'middle_name' => $student->middle_name,
                'last_name' => $student->middle_name,
                'address1' => $student->street_address1,
                'address2' => $student->street_address2,
                'city' => $student->city,
                'province' => $student->province,
                'postal_code' => $student->postal_code,
                'phone_number' => $student->phone_number,
            ]);
        }

        return true;
    }

    private function createApplications(BursaryPeriod $bP)
    {
        $now = Carbon::now();
        $potentials = ElPotential::where('bursary_period_id', $bP->id)->get();

        foreach ($potentials as $potential) {
            $app = Application::firstOrCreate([
                'student_id' => $potential->student->id,
                'sin' => $potential->sin,
                'application_number' => $potential->application_number,
                'bursary_period_id' => $potential->bursary_period_id,
                'eligible' => $potential->eligibility === 'Eligible',
                'award_status' => $potential->award_or_deny === 'Award' ? 'Approved' : 'Denied',
                'effective_date' => $now,
                'receive_date' => $potential->receive_date,
                'program_code' => 'NEB',
            ]);

            Neb::create([
                'application_id' => $app->id,
                'inst_code' => $potential->inst_code,
                'study_start_date' => $potential->study_start_date,
                'study_end_date' => $potential->study_end_date,
                'bursary_period_id' => $bP->id,
                'sfas_program_code' => $potential->program_code,
                'declined_removed_reason' => $potential->neb_deny_reason,
                'neb_ineligible_reason' => $potential->neb_ineligible_reason,
                'award_amount' => $potential->award_amount == null ? 0 : $potential->award_amount,
                'unmet_need' => $potential->total_unmet_need == null ? 0 : $potential->total_unmet_need,
                'weeks_of_study' => $potential->weeks_of_study == null ? 0 : $potential->weeks_of_study,
                'weekly_unmet_need' => $potential->weekly_unmet_need == null ? 0 : $potential->weekly_unmet_need,
                'assessed_need_amount' => $potential->assessed_need_amount == null ? 0 : $potential->assessed_need_amount,
                'nurse_type' => $potential->nurse_type,
                'sector' => $potential->sector,
                'sfas_award_id' => $potential->sfas_award_id,
                'valid_institution' => $potential->valid_institution,
                'restriction' => $potential->restriction,
                'awarded_in_prior_year' => $potential->awarded_in_prior_year,
                'withdrawal' => $potential->withdrawal,
            ]);
        }

        return true;
    }

    private function cleanup(BursaryPeriod $bP)
    {

        ElPotential::where('bursary_period_id', $bP->id)->update(['finalized' => true]);
        $bP->awarded = true;
        $bP->save();

        return true;
    }

    public function processNeb(Request $request)
    {
        ini_set('memory_limit', '512M');

        $bursaryPeriod = BursaryPeriod::find($request->input('bursary_period_id'));

        if (! $bursaryPeriod) {
            return null;
        }

        if ($bursaryPeriod->awarded) {
            return Inertia::render('Neb::Error', ['status' => 500, 'message' => 'This bursary period has already been awarded.']);
        }

        $this->formattedBpsd = Carbon::parse($bursaryPeriod->bursary_period_start_date)->toDateString();
        $this->formattedBped = Carbon::parse($bursaryPeriod->bursary_period_end_date)->format('Y-m-d');
        $programCodes = SfasProgram::where('eligible', true)->pluck('sfas_program_code');
        $this->programCodesString = $programCodes->map(function ($row) {
            return '\''.$row.'\'';
        })->join(', ');

        switch ($request->input('step')) {
            case 0:
                $this->nebElPotentials($bursaryPeriod);
                break;
            case 1:
                $this->monthOverlap($bursaryPeriod);
                break;
            case 2:
                $this->validInstitution($bursaryPeriod);
                break;
            case 3:
                $this->restriction($bursaryPeriod);
                break;
            case 4:
                $this->awardedInPriorYear($bursaryPeriod);
                break;
            case 5:
                $this->withdrawal($bursaryPeriod);
                break;
            case 6:
                $this->nurseType($bursaryPeriod);
                break;
            case 7:
                $this->eligibility($bursaryPeriod);
                break;
            case 8:
                $this->rank($bursaryPeriod);
                break;
            case 9:
                $this->awardOrDeny($bursaryPeriod);
                break;
            case 10:
                $this->awardAmount($bursaryPeriod);
                break;
            case 11:
                $this->sfasAwardId($bursaryPeriod);
                \Log::info('Processing Complete!');
                break;
            default:
                break;
        }

        return Redirect::to('/neb/bursary-periods/show/'.$bursaryPeriod->id);
    }

    /**
     * Display a listing of the resource.
     *
     * @input bp: BursaryPeriod object
     *
     * @return \Illuminate\Database\Eloquent\Collection.json
     */
    private function nebElPotentials(BursaryPeriod $bP)
    {
        if ($bP == null) {
            return null;
        }

        \Log::info('Starting process for BP: ' . $this->formattedBpsd . ' - ' . $this->formattedBped);
        \Log::info('processing: nebElPotentials');

        // Cleanup for new run
        DB::connection(env('DB_DATABASE_NEB'))->statement('TRUNCATE table el_sin_py_ssds RESTART IDENTITY');
        DB::connection(env('DB_DATABASE_NEB'))->statement('TRUNCATE table el_potential_restrictions RESTART IDENTITY');
        DB::connection(env('DB_DATABASE_NEB'))->statement('TRUNCATE table el_potential_restriction_details RESTART IDENTITY');

        // Remove all records entered for the same bursary period from previous runs
        ElPotential::where('bursary_period_id', $bP->id)->delete();

        $env_query1 = env('NEB_POTENTIALS_QUERY1');
        $qry = sprintf($env_query1, $this->programCodesString, $this->formattedBpsd, $this->formattedBped, $this->formattedBpsd, $this->formattedBped, $this->formattedBpsd, $this->formattedBped);

        $strSQL1 = DB::connection('oracle')->select($qry);
        $elSinPySsds = [];
        foreach ($strSQL1 as $row) {
            $elSinPySsds[] = [
                'sin' => $row->sin,
                'max_program_year' => $row->max_program_year,
                'max_study_start_date' => $row->max_study_start_dte,
            ];
        }
        ElSinPySsd::insert($elSinPySsds);
        $elSinPySsds = null;

        $env_query2 = env('NEB_POTENTIALS_QUERY2');
        $qry = sprintf($env_query2, $this->programCodesString, $this->formattedBpsd, $this->formattedBped, $this->formattedBpsd, $this->formattedBped, $this->formattedBpsd, $this->formattedBped);
        $strSQL2 = DB::connection('oracle')->select($qry);
        $user = Auth::user();

        for ($i = 0; $i < count($strSQL2); $i++) {
            $row = $strSQL2[$i];
            $check = ElSinPySsd::where('sin', $row->lngsin)
                ->where('max_program_year', $row->strprogramyear)
                ->where('max_study_start_date', $row->dtmstudystartdate)
                ->first();

            if ($check != null) {
                ElPotential::create([
                    'bursary_period_id' => $bP->id,
                    'application_number' => $row->lngapplicationnumber,
                    'sin' => $row->lngsin,
                    'postal_code' => $row->strpostalcode ? trim($row->strpostalcode) : null,
                    'birth_date' => $row->dtmbirthdate,
                    'first_name' => $row->strfirstname ? trim($row->strfirstname) : null,
                    'middle_name' => $row->strmiddlename ? trim($row->strmiddlename) : null,
                    'last_name' => $row->strlastname ? trim($row->strlastname) : null,
                    'assessed_need_amount' => $row->curassessedneedamt,
                    'total_unmet_need' => $row->curtotalunmetneed,
                    'weeks_of_study' => $row->lngweeksofstudy,
                    'weekly_unmet_need' => $row->curweeklyunmetneed,
                    'program_year' => $row->strprogramyear ? trim($row->strprogramyear) : null,
                    'street_address1' => $row->straddress1 ? trim($row->straddress1) : null,
                    'street_address2' => $row->straddress2 ? trim($row->straddress2) : null,
                    'city' => $row->strcity ? trim($row->strcity) : null,
                    'province' => $row->strprovince ? trim($row->strprovince) : null,
                    'gender' => $row->strgender ? trim($row->strgender) : null,
                    'phone_number' => $row->strphonenumber,
                    'study_start_date' => $row->dtmstudystartdate,
                    'study_end_date' => $row->dtmstudyenddate,
                    'institution_name' => $row->strinstitutionname ? trim($row->strinstitutionname) : null,
                    'program_code' => $row->strprogramcode ? trim($row->strprogramcode) : null,
                    'inst_code' => $row->strinstcode ? trim($row->strinstcode) : null,
                    'area_of_study' => $row->strareaofstudy ? trim($row->strareaofstudy) : null,
                    'degree_level' => $row->strdegreelevel ? trim($row->strdegreelevel) : null,
                    'sector' => $row->private_flg == 'Y' ? 'Private' : 'Public',
                    'eligibility' => 'Eligible',
                    'restriction' => false,
                    'awarded_in_prior_year' => false,
                    'withdrawal' => false,
                    'award_amount' => 0,
                    'valid_institution' => false,
                    'receive_date' => $row->receive_dte,
                    'supplier_no' => $row->supplier_no,
                    'created_by' => $user->user_id,
                    'finalized' => false,
                ]);
            }
        }

        return ElPotential::all();
    }

    private function monthOverlap(BursaryPeriod $bP)
    {
        \Log::info('processing: monthOverlap');
        $potentials = ElPotential::where('bursary_period_id', $bP->id)->get();

        if ($potentials->isEmpty()) {
            return [];
        }

        foreach ($potentials as $potential) {
            if (
                $potential->study_start_date > $potential->study_end_date ||
                $potential->study_end_date < $bP->bursary_period_start_date ||
                $potential->study_start_date > $bP->bursary_period_end_date
            ) {
                // No overlap
                $potential->num_day_overlap = 0;
                $potential->month_overlap = false;
                $potential->save();
            } else {
                $rangeStartDate = $potential->study_start_date < $bP->bursary_period_start_date
                    ? $bP->bursary_period_start_date
                    : $potential->study_start_date;

                $rangeEndDate = $potential->study_end_date < $bP->bursary_period_end_date
                    ? $potential->study_end_date
                    : $bP->bursary_period_end_date;

                $startDateTime = Carbon::parse($rangeStartDate);
                $endDateTime = Carbon::parse($rangeEndDate);
                $numDays = $endDateTime->diffInDays($startDateTime);

                $potential->num_day_overlap = $numDays;
                $potential->month_overlap = $numDays >= 30;
                $potential->save();
            }
        }

        return $potentials;
    }

    private function validInstitution(BursaryPeriod $bP)
    {
        \Log::info('processing: validInstitution');
        $envQuery1 = env('VALIDATE_INSTITUTION_QUERY1');
        $strSQL3 = DB::connection('oracle')->select($envQuery1);
        $instCodeArray = array_map(function ($row) {
            return trim($row->institution_code);
        }, $strSQL3);

        $check = ElPotential::where('bursary_period_id', $bP->id)
            ->whereIn('inst_code', $instCodeArray)->get();

        foreach ($check as $entry) {
            $entry->valid_institution = true;
            $entry->save();
        }

        return $strSQL3;
    }

    private function restriction(BursaryPeriod $bP)
    {
        $restrictionCodes = Restriction::select('restriction_code')->get();
        $restrictionString = $restrictionCodes->pluck('restriction_code')->map(function ($row) {
            return "'".$row."'";
        })->join(', ');

        $envQuery1 = env('RESTRICTIONS_QUERY1');
        $qry = sprintf($envQuery1, $restrictionString, $this->programCodesString, $this->formattedBpsd, $this->formattedBped, $this->formattedBpsd, $this->formattedBped, $this->formattedBpsd, $this->formattedBped);

        $strSQL = DB::connection('oracle')->select($qry);
        $restrictedSinArray = array_map(function ($row) {
            return $row->sin;
        }, $strSQL);

        $elPotSin = ElPotential::select('sin', 'bursary_period_id')->where('bursary_period_id', $bP->id)
            ->whereIn('sin', $restrictedSinArray)->get();

        foreach ($elPotSin as $row) {
            ElPotentialRestriction::create([
                'sin' => $row->sin,
                'bursary_period_id' => $row->bursary_period_id,
            ]);
        }
        Log::info('Finished: ElPotentialRestriction.create');

        $restrictionSinsList = ElPotentialRestriction::where('bursary_period_id', $bP->id)->select('sin')->get();
        //        $restrictionSinsArray = $restrictionSinsList->pluck('sin');
        Log::info('Restricted SINs: '.$restrictionSinsList->count());

        //        DB::table('el_potential')->whereIn('sin', $restrictionSinsArray)->update(['restriction' => true]);
        ElPotential::where('bursary_period_id', $bP->id)
            ->whereIn('sin', $restrictionSinsList)->update(['restriction' => true]);
        $envQuery2 = env('RESTRICTIONS_QUERY2');
        $qry2 = sprintf($envQuery2, $restrictionSinsList->pluck('sin')->map(function ($row) {
            return "'".$row."'";
        })->join(', '), $restrictionString);

        $strSQL2 = DB::connection('oracle')->select($qry2);

        foreach ($strSQL2 as $row) {
            ElPotentialRestrictionDetail::create([
                'sin' => $row->sin,
                'bursary_period_id' => $bP->id,
                'restriction_code' => $row->restriction_code,
                'restriction_description' => $row->restriction_typ,
                'applied_date' => $row->applied_dte,
            ]);

        }

        return $strSQL2;
    }

    private function awardedInPriorYear(BursaryPeriod $bP)
    {
        $bpLastTwo = BursaryPeriod::select('id')->whereNot('id', $bP->id)->orderBy('id', 'desc')->limit(2)->get();
        $awardedApps = Application::distinct('applications.sin')
            ->join('nebs', 'nebs.application_id', '=', 'applications.id')
            ->where('award_status', 'Approved')
            ->whereIn('nebs.bursary_period_id', $bpLastTwo->pluck('id'))
            ->get()
            ->pluck('sin');

        return ElPotential::where('bursary_period_id', $bP->id)->whereIn('sin', $awardedApps)->update(['awarded_in_prior_year' => true]);
    }

    private function withdrawal(BursaryPeriod $bP)
    {
        $envQuery = env('WITHDRAWALS_QUERY1');
        $qry = sprintf($envQuery, $this->programCodesString, $this->formattedBpsd, $this->formattedBped, $this->formattedBpsd, $this->formattedBped, $this->formattedBpsd, $this->formattedBped);

        $strSQL = DB::connection('oracle')->select($qry);
        $restrictedSinArray = array_column($strSQL, 'sin');

        return ElPotential::where('bursary_period_id', $bP->id)
            ->whereIn('sin', $restrictedSinArray)->update(['withdrawal' => true]);
    }

    private function nurseType(BursaryPeriod $bP)
    {
        $elPot = ElPotential::where('bursary_period_id', $bP->id)
            ->select('el_potentials.id', 'sfas_programs.nurse_type')
            ->join('sfas_programs', 'sfas_programs.sfas_program_code', 'el_potentials.program_code')
            ->get();

        foreach ($elPot as $item) {
            ElPotential::where('id', $item->id)->update(['nurse_type' => $item->nurse_type]);
        }

        return $elPot;
    }

    private function eligibility(BursaryPeriod $bP)
    {
        $elPot = ElPotential::where('bursary_period_id', $bP->id)->get();

        foreach ($elPot as $item) {
            if ($item->valid_institution === false) {
                $item->eligibility = 'Ineligible';
                $item->neb_ineligible_reason = 'Ineligible Institution';
            }
            if ($item->restriction === true) {
                $item->eligibility = 'Ineligible';
                $item->neb_ineligible_reason = 'Restriction';
            }
            if ($item->awarded_in_prior_year === true) {
                $item->eligibility = 'Ineligible';
                $item->neb_ineligible_reason = 'Awarded within prior two bursary periods';
            }
            if ($item->total_unmet_need <= 0) {
                $item->eligibility = 'Ineligible';
                $item->neb_ineligible_reason = 'No unmet need';
            }
            if ($item->withdrawal === true) {
                $item->eligibility = 'Ineligible';
                $item->neb_ineligible_reason = 'Withdrawal';
            }
            if ($item->month_overlap === false) {
                $item->eligibility = 'Ineligible';
                $item->neb_ineligible_reason = 'No 30-day overlap between bursary period and study period';
            }
            $item->save();
        }

        return $elPot;
    }

    private function rank(BursaryPeriod $bP)
    {
        // RankByUnmetNeed
        $elPot = ElPotential::where('bursary_period_id', $bP->id)
            ->where('eligibility', 'Eligible')->orderBy('weekly_unmet_need', 'desc')->get();
        foreach ($elPot as $key => $item) {
            $item->rank_by_unmet_need = $key + 1;
            $item->save();
        }

        // RankByUnmetNeed("RN")
        $elPot = ElPotential::where('bursary_period_id', $bP->id)
            ->where('eligibility', 'Eligible')
            ->where('nurse_type', 'RN')
            ->orderBy('weekly_unmet_need', 'desc')
            ->get();
        foreach ($elPot as $key => $item) {
            $item->rank_by_nurse_type = $key + 1;
            $item->save();
        }

        // RankByUnmetNeed("LPN")
        $elPot = ElPotential::where('bursary_period_id', $bP->id)
            ->where('eligibility', 'Eligible')
            ->where('nurse_type', 'LPN')
            ->orderBy('weekly_unmet_need', 'desc')
            ->get();
        foreach ($elPot as $key => $item) {
            $item->rank_by_nurse_type = $key + 1;
            $item->save();
        }

        // RankBySector("Private")
        $elPot = ElPotential::where('bursary_period_id', $bP->id)
            ->where('eligibility', 'Eligible')
            ->where('sector', 'Private')
            ->orderBy('weekly_unmet_need', 'desc')
            ->get();
        foreach ($elPot as $key => $item) {
            $item->rank_by_sector = $key + 1;
            $item->save();
        }

        // RankBySector("Public")
        $elPot = ElPotential::where('bursary_period_id', $bP->id)
            ->where('eligibility', 'Eligible')
            ->where('sector', 'Public')
            ->orderBy('weekly_unmet_need', 'desc')
            ->get();
        foreach ($elPot as $key => $item) {
            $item->rank_by_sector = $key + 1;
            $item->save();
        }

        return true;
    }

    private function awardOrDeny(BursaryPeriod $bP)
    {
        $defaultAward = $bP->default_award ?? 0;
        $periodBudget = $bP->period_budget ?? 0;
        $rNPortion = $bP->rn_budget ?? 0;
        $pubSecPortion = $bP->public_sector_budget ?? 0;

        // AwardByNurseType
        if ($bP->budget_allocation_type == 'Nurse Type') {
            $numRNRecipient = round(($periodBudget * ($rNPortion / 100)) / $defaultAward);
            $numLPNRecipient = round(($periodBudget * ((100 - $rNPortion) / 100)) / $defaultAward);
            ElPotential::where('bursary_period_id', $bP->id)->where('nurse_type', 'RN')
                ->where('rank_by_nurse_type', '<=', $numRNRecipient)
                ->update(['award_or_deny' => 'Award']);
            ElPotential::where('bursary_period_id', $bP->id)->where('nurse_type', 'LPN')
                ->where('rank_by_nurse_type', '<=', $numLPNRecipient)
                ->update(['award_or_deny' => 'Award']);
        } // AwardBySector
        elseif ($bP->budget_allocation_type == 'Sector') {
            $numPublicRecipient = round(($periodBudget * ($pubSecPortion / 100)) / $defaultAward);
            $numPrivateRecipient = round(($periodBudget * ((100 - $pubSecPortion) / 100)) / $defaultAward);
            ElPotential::where('bursary_period_id', $bP->id)->where('sector', 'Public')
                ->where('rank_by_sector', '<=', $numPublicRecipient)
                ->update(['award_or_deny' => 'Award']);
            ElPotential::where('bursary_period_id', $bP->id)->where('sector', 'Private')
                ->where('rank_by_sector', '<=', $numPrivateRecipient)
                ->update(['award_or_deny' => 'Award']);
        } // AwardByUnmetNeed
        else {
            $numRecipient = round($periodBudget / $defaultAward);
            ElPotential::where('bursary_period_id', $bP->id)->where('rank_by_unmet_need', '<=', $numRecipient)
                ->update(['award_or_deny' => 'Award']);
        }

        ElPotential::where('bursary_period_id', $bP->id)->where('eligibility', 'Ineligible')
            ->update([
                'neb_deny_reason' => 'Ineligible',
                'award_or_deny' => 'Deny',
            ]);

        ElPotential::where('bursary_period_id', $bP->id)->whereNull('award_or_deny')
            ->update(['award_or_deny' => 'Deny']);

        ElPotential::where('bursary_period_id', $bP->id)->where('eligibility', 'Eligible')
            ->where('award_or_deny', 'Deny')
            ->update(['neb_deny_reason' => 'Ministry budget for period surpassed']);

        Log::info('Finished awardOrDeny');

        return true;
    }

    public function awardAmount(BursaryPeriod $bP)
    {
        ElPotential::where('bursary_period_id', $bP->id)->where('award_or_deny', 'Award')
            ->update(['award_amount' => $bP->default_award]);

        return true;
    }

    public function sfasAwardId(BursaryPeriod $bP)
    {
        $strSQL = Neb::whereNotNull('sfas_award_id')
            ->select('sfas_award_id')
            ->orderBy('sfas_award_id', 'desc')
            ->first();

        if ($strSQL != null && $strSQL->sfas_award_id != null) {
            $nextSfasId = $strSQL->sfas_award_id + 1;
            $elPot = ElPotential::where('bursary_period_id', $bP->id)
                ->where('award_or_deny', 'Award')
                ->orderBy('application_number', 'asc')
                ->get();

            foreach ($elPot as $key => $pot) {
                $pot->sfas_award_id = $nextSfasId + $key;
                $pot->save();
            }
            Log::info('Finished: sfasAwardId');

            return $strSQL;
        }

        return null;
    }

    public function awardedTextLine($record, $bp)
    {
        $line = 'SP04';
        $line .= $this->padStringWithSpaces(date('Ymd', strtotime($bp->bursary_period_start_date)), 8);
        $line .= $this->padStringWithSpaces(date('Ymd', strtotime($bp->bursary_period_end_date)), 8);
        $line .= $this->padStringWithSpaces($record->sfas_award_id, 6, true);
        $line .= $this->padStringWithSpaces($record->sin, 9);
        $line .= $this->padStringWithSpaces($record->last_name, 25);
        $line .= $this->padStringWithSpaces($record->first_name, 15);
        $line .= $this->padStringWithSpaces($record->middle_name, 1);
        $line .= $this->padStringWithSpaces($record->street_address1, 40);
        $line .= $this->padStringWithSpaces($record->street_address2, 40);
        $line .= $this->padStringWithSpaces($record->city, 25);
        $line .= $this->padStringWithSpaces(($record->province == 'British Columbia' ? 'BC' : $record->province), 4);
        $line .= $this->padStringWithSpaces($record->postal_code, 10);
        $line .= 'CAN '; // Country
        $line .= $this->padStringWithSpaces(date('Ymd', strtotime($record->birth_date)), 8);
        $line .= $this->padStringWithSpaces($record->gender, 1);
        $line .= $this->padStringWithSpaces('', 50); // Email is unknown
        $line .= $this->padStringWithSpaces($record->phone_number, 10);
        $line .= 'CA'; // Citizenship is unknown; default value CA
        $line .= $this->padStringWithSpaces(date('Ymd', strtotime($record->study_start_date)), 8);
        $line .= $this->padStringWithSpaces(date('Ymd', strtotime($record->study_end_date)), 8);
        $line .= $this->padStringWithSpaces($record->weekly_unmet_need, 8, true);
        $line .= $this->padStringWithSpaces('', 10); // Student number is unknown
        $line .= $this->padStringWithSpaces($record->inst_code, 4);
        $line .= $this->padStringWithSpaces($record->area_of_study, 30);
        $line .= 'F'; // Full-time flag
        $line .= $this->padStringWithSpaces($record->award_amount * 100, 8, true);
        $line .= $this->padStringWithSpaces((new \DateTime())->format('Ymd'), 8);
        $line .= $this->padStringWithSpaces('', 8); // Redeposit date
        $line .= $this->padStringWithSpaces('', 4); // Redeposit date
        $line .= $this->padStringWithSpaces((new \DateTime())->format('Ymd'), 8);
        $line .= $this->padStringWithSpaces((new \DateTime())->format('Ymd'), 8);
        $line .= 'HOME'; // Mail to
        $line .= 'N'; // Final flag
        $line .= 'N'; // Manual flag

        return $line;
    }

    private function prepareCsvLine($record)
    {
        $csvValues = [
            $record->application_number,
            $record->sin,
            $record->postal_code,
            $record->birth_date,
            '"'.$record->first_name.'"',
            '"'.$record->middle_name.'"',
            '"'.$record->last_name.'"',
            $record->assessed_need_amount,
            $record->total_unmet_need,
            $record->weeks_of_study,
            $record->weekly_unmet_need,
            $record->program_year,
            '"'.$record->street_address1.'"',
            '"'.$record->street_address2.'"',
            '"'.$record->city.'"',
            '"'.$record->province.'"',
            $record->gender,
            $record->phone_number,
            $record->study_start_date,
            $record->study_end_date,
            '"'.$record->institution_name.'"',
            $record->program_code,
            $record->inst_code,
            '"'.$record->area_of_study.'"',
            $record->degree_level,
            $record->bursary_period_id,
            $record->month_overlap,
            $record->num_day_overlap,
            $record->valid_institution,
            $record->restriction,
            $record->awarded_in_prior_year,
            $record->withdrawal,
            $record->nurse_type,
            $record->sector,
            $record->eligibility,
            $record->neb_ineligible_reason,
            $record->rank_by_unmet_need,
            $record->rank_by_nurse_type,
            $record->rank_by_sector,
            $record->award_or_deny,
            $record->neb_deny_reason,
            $record->award_amount,
            $record->sfas_award_id,
            $record->supplier_no,
        ];

        return implode(',', $csvValues);
    }

    private function padStringWithSpaces($inputString, $desiredLength, $preFixWithZero = false)
    {
        if ($inputString === null) {
            $inputString = '';
        }

        if (! is_string($inputString)) {
            $inputString = strval($inputString); // Convert to string if not already
        }

        $inputString = trim($inputString);

        if (strlen($inputString) === $desiredLength) {
            return $inputString; // Already the desired length, return as is
        } elseif (strlen($inputString) > $desiredLength) {
            return substr($inputString, 0, $desiredLength); // Truncate to desired length
        } else {
            $spacesToAdd = $desiredLength - strlen($inputString);
            if ($preFixWithZero) {
                $paddingCharacter = '0';

                return str_repeat($paddingCharacter, $spacesToAdd).$inputString;
            }

            return $inputString.str_repeat(' ', $spacesToAdd);
        }
    }
}
