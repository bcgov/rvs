<?php

namespace Modules\Vss\Entities;

class Institution extends ModuleModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['institution_code', 'institution_name', 'institution_location_code', 'institution_type_code'];

    public function incidents()
    {
        return $this->hasMany('Modules\Vss\Entities\Incident', 'institution_code', 'institution_code');
    }
}
