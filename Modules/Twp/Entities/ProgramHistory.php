<?php

namespace Modules\Twp\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * @return BelongsTo<Program, ProgramHistory>
     */
    public function program(): BelongsTo {
        return $this->belongsTo(Program::class, 'program_twp_id', 'id');
    }

    /**
     * @return BelongsTo<Student, ProgramHistory>
     */
    public function student(): BelongsTo {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
