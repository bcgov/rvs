<?php

namespace Modules\Plsc\Entities;

class Institution extends ModuleModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'contact_name', 'contact_email', 'active_flag'];

    public function applications()
    {
        return $this->hasMany('Modules\Plsc\Entities\Application');
    }
}
