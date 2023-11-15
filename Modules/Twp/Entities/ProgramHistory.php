<?php

namespace Modules\Twp\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramHistory extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['student_id', 'study_period_start_date', 'institution_name', 'credential',
        'program_length', 'program_length_type', 'total_estimated_cost', 'student_status', 'comments', 'credential_type',
        'institution_twp_id', 'program_twp_id', 'application_id', 'study_field', ];

    public function program()
    {
        return $this->belongsTo('Modules\Twp\Entities\Program', 'program_twp_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo('Modules\Twp\Entities\Student', 'student_id', 'id');
    }
}
