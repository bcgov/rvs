<?php

namespace Modules\Plsc\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $student_id
 * @property int $institution_id
 * @property int|null $app_idx
 * @property int|null $individual_idx
 * @property int|null $application_year
 * @property string|null $program_of_study
 * @property string|null $status_code
 * @property string|null $comment
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Student $student
 * @property-read Institution $institution
 * @property-read object|null $sfas_app
 */
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


    /**
     * @return BelongsTo<Student, Application>
     */
    public function student(): BelongsTo {
        return $this->belongsTo(Student::class);
    }


    /**
     * @return BelongsTo<Institution, Application>
     */
    public function institution(): BelongsTo {
        return $this->belongsTo(Institution::class);
    }

    /**
     * @return object|null
     */
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
