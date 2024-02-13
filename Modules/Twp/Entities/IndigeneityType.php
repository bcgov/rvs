<?php

namespace Modules\Twp\Entities;

class IndigeneityType extends ModuleModel
{

    protected $fillable = ['title', 'is_active'];

    public function students()
    {
        return $this->belongsToMany(
            'Modules\Twp\Entities\Student', 'indigeneity_student','student_id',
            'indigeneity_id');
    }

}
