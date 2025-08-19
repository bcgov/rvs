<?php

namespace Modules\Neb\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElPotentialRestriction extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sin', 'bursary_period_id',
    ];

    /**
     * @return BelongsTo<Student, ElPotentialRestriction>
     */
    public function student(): BelongsTo {
        return $this->belongsTo(Student::class, 'sin', 'sin');
    }

    /**
     * @return BelongsTo<BursaryPeriod, ElPotentialRestriction>
     */
    public function bursaryPeriod(): BelongsTo
    {
        return $this->belongsTo(BursaryPeriod::class, 'bursary_period_id', 'id');
    }
}
