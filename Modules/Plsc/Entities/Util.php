<?php

namespace Modules\Plsc\Entities;

use Carbon\Carbon;

/**
 * @property int $id
 * @property string $field_name
 * @property string $field_type
 * @property bool $active_flag
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Util extends ModuleModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['field_name', 'field_type', 'active_flag'];

}
