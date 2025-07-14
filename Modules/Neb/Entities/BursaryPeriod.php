<?php

namespace Modules\Neb\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string|null $bursary_period_start_date
 * @property string|null $bursary_period_end_date
 * @property bool $awarded Indicates if awards are distributed for period (yes or no)
 * @property float|null $default_award The default amount of award for period
 * @property float|null $period_budget Total budget amount for period
 * @property int|null $rn_budget Number between 1 and 100; portion of budget to allocate to RN programs
 * @property int|null $public_sector_budget Number between 1 and 100; portion of budget to allocate to public sector programs
 * @property string|null $budget_allocation_type Method of allocating budget; either by sector (private/public), nurse type (LPN/RN), or none
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * // Accessors
 * @property string $bpsd Formatted start date (y-m-d)
 * @property string $bped Formatted end date (y-m-d)
 */
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

    /**
     * @return string
     */
    public function getBpsdAttribute(): string {
        return date('y-m-d', strtotime($this->bursary_period_start_date));
    }

    /**
     * @return string
     */
    public function getBpedAttribute(): string {
        return date('y-m-d', strtotime($this->bursary_period_end_date));
    }
}
