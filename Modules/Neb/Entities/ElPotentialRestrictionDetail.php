<?php

namespace Modules\Neb\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function student()
    {
        return $this->belongsTo('Modules\Neb\Entities\Student', 'sin', 'sin');
    }

    public function bursaryPeriod()
    {
        return $this->belongsTo('Modules\Neb\Entities\BursaryPeriod', 'bursary_period_id', 'id');
    }

    public function restriction()
    {
        return $this->belongsTo('Modules\Neb\Entities\Restriction', 'restriction_code', 'restriction_code');
    }
}
