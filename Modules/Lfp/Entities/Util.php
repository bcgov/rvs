<?php

namespace Modules\Lfp\Entities;

/**
 * @property string $field_name
 * @property string $field_type
 * @property bool $active_flag
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
