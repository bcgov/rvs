<?php

namespace Modules\Neb\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BursaryPeriod extends ModuleModel
{
    use HasFactory;

    protected $appends = ['bpsd', 'bped'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bursary_period_start_date',
        'bursary_period_end_date',
        'awarded',
        'default_award',
        'period_budget',
        'rn_budget',
        'public_sector_budget',
        'budget_allocation_type',
    ];

    public function getBpsdAttribute()
    {
        return date('y-m-d', strtotime($this->bursary_period_start_date));
    }

    public function getBpedAttribute()
    {
        return date('y-m-d', strtotime($this->bursary_period_end_date));
    }
}
