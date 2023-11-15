<?php

namespace Modules\Yeaf\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

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
