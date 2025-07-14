<?php

namespace Modules\Neb\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id', 'sin', 'application_number', 'complete', 'eligible', 'award_status', 'rank', 'total_score', 'receive_date',
        'effective_date', 'process_date', 'comment', 'program_code', 'bursary_period_id', ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Student, Application>
     */
    public function student(): BelongsTo {
        return $this->belongsTo('Modules\Neb\Entities\Student', 'sin', 'sin');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<BursaryPeriod, Application>
     */
    public function bursaryPeriod(): BelongsTo
    {
        return $this->belongsTo('Modules\Neb\Entities\BursaryPeriod', 'bursary_period_id', 'id');
    }
}
