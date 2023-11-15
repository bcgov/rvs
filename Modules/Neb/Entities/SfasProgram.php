<?php

namespace Modules\Neb\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SfasProgram extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'neb_program_code', 'sfas_program_code', 'area_of_study', 'degree_level', 'nurse_type', 'eligible',
    ];
}
