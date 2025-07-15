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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Student, ElPotentialRestrictionDetail>
     */
    public function student(): BelongsTo {
        return $this->belongsTo('Modules\Neb\Entities\Student', 'sin', 'sin');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<BursaryPeriod, ElPotentialRestrictionDetail>
     */
    public function bursaryPeriod(): BelongsTo
    {
        return $this->belongsTo('Modules\Neb\Entities\BursaryPeriod', 'bursary_period_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Restriction, ElPotentialRestrictionDetail>
     */
    public function restriction(): BelongsTo {
        return $this->belongsTo('Modules\Neb\Entities\Restriction', 'restriction_code', 'restriction_code');
    }
}
