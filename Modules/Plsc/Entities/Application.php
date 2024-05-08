<?php

namespace Modules\Plsc\Entities;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends ModuleModel
{
    use SoftDeletes;

//    protected $appends = ['sfas_ind', 'sfas_app'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id', 'institution_id', 'application_year', 'receive_date', 'ssd', 'sed', 'program_of_study', 'credential',
        'parent_last_name', 'parent_first_name', 'parent_employee_id', 'parent_department_id', 'parent_address',
        'parent_city', 'parent_province', 'parent_postal_code', 'parent_phone_number', 'parent_email', 'parent_ministry',
        'parent_branch', 'parent_job_title', 'parent_three_years_in_gov', 'high_school_average', 'post_secondary_average',
        'seven_fifty_word_essay', 'high_school_transcript', 'post_secondary_transcript', 'student_reference_letter',
        'communication_skills', 'enrollment_confirmed', 'forward_to_committee', 'status_code', 'other_org', 'comment',
        'application_id', 'app_idx', 'individual_idx',
    ];


    public function student()
    {
        return $this->belongsTo('Modules\Plsc\Entities\Student');
    }


    public function institution()
    {
        return $this->belongsTo('Modules\Plsc\Entities\Institution');
    }

    public function getSfasAppAttribute(): ?object
    {
        $appId = $this->attributes['app_idx'];
        if(is_null($appId)) return null;

        $awayPayment = DB::connection('oracle')
            ->select(env("PLSC_SFA_APP") . $appId);

        // Convert the result to an object
        return $awayPayment ? (object) $awayPayment[0] : null;
    }
}
