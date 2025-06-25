<?php

namespace Modules\Yeaf\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $program_year_id
 * @property int $year_start
 * @property int $year_end
 * @property float $grant_amount
 * @property int $max_years_allowed
 * @property int $min_age
 * @property int $max_age
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class ProgramYear extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'year_start', 'year_end', 'grant_amount', 'max_years_allowed', 'min_age', 'max_age', ];
}
