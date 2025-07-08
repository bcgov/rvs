<?php

namespace Modules\Twp\Entities;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class IndigeneityType extends ModuleModel
{

    protected $fillable = ['title', 'is_active'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Student>
     */
    public function students(): BelongsToMany {
        return $this->belongsToMany(
            'Modules\Twp\Entities\Student', 'indigeneity_type_student');
    }

}
