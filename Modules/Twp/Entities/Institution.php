<?php

namespace Modules\Twp\Entities;


class Institution extends ModuleModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'contact_name', 'contact_email', 'active_flag'];

    public function programs()
    {
        return $this->hasMany('Modules\Twp\Entities\Program', 'institution_twp_id', 'id');
    }
}
