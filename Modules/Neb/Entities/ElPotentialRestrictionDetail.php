<?php

namespace Modules\Neb\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElPotentialRestrictionDetail extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sin', 'bursary_period_id', 'restriction_code', 'restriction_description', 'applied_date',
    ];

    /**
     * @return BelongsTo<Student, ElPotentialRestrictionDetail>
     */
    public function student(): BelongsTo {
        return $this->belongsTo(Student::class, 'sin', 'sin');
    }

    /**
     * @return BelongsTo<BursaryPeriod, ElPotentialRestrictionDetail>
     */
    public function bursaryPeriod(): BelongsTo
    {
        return $this->belongsTo(BursaryPeriod::class, 'bursary_period_id', 'id');
    }

    /**
     * @return BelongsTo<Restriction, ElPotentialRestrictionDetail>
     */
    public function restriction(): BelongsTo {
        return $this->belongsTo(Restriction::class, 'restriction_code', 'restriction_code');
    }
}
