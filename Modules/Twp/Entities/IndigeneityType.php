<?php

namespace Modules\Twp\Entities;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string|null $title
 * @property bool $active_flag
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
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
