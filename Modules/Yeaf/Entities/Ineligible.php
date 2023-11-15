<?php

namespace Modules\Yeaf\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ineligible extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['code_id', 'active_flag', 'code_type', 'description', 'paragraph_text'];
}
