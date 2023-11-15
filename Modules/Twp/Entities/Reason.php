<?php

namespace Modules\Twp\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reason extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['reason_status', 'title', 'letter_body', 'active_flag'];
}
