<?php

namespace Modules\Twp\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string|null $reason_status
 * @property string|null $title
 * @property string|null $letter_body
 * @property bool $active_flag
 */
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
