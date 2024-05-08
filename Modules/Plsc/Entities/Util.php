<?php

namespace Modules\Plsc\Entities;

class Util extends ModuleModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['field_name', 'field_type', 'active_flag'];

}
