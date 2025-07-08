<?php

namespace Modules\Twp\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['student_id', 'study_period_start_date', 'institution_name', 'credential',
        'program_length', 'program_length_type', 'total_estimated_cost', 'student_status', 'comments', 'credential_type',
        'institution_twp_id', 'application_id', 'study_field', ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Student, Program>
     */
    public function student(): BelongsTo {
        return $this->belongsTo('Modules\Twp\Entities\Student', 'student_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Application, Program>
     */
    public function application(): BelongsTo {
        return $this->belongsTo('Modules\Twp\Entities\Application', 'application_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Institution, Program>
     */
    public function institution(): BelongsTo {
        return $this->belongsTo('Modules\Twp\Entities\Institution', 'institution_twp_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<ProgramHistory>
     */
    public function versions(): HasMany {
        return $this->hasMany('Modules\Twp\Entities\ProgramHistory', 'program_twp_id', 'id')->orderBy('created_at', 'desc');
    }
}
