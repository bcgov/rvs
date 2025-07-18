<?php

namespace Modules\Twp\Entities;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 *
 * @property int $id
 * @property string|null $title
 * @property bool $active_flag
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class PaymentType extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['title', 'active_flag'];
}
