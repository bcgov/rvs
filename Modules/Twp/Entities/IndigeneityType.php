<?php

namespace Modules\Twp\Entities;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string|null $title
 * @property bool $active_flag
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class IndigeneityType extends ModuleModel
{

    protected $fillable = ['title', 'is_active'];

    /**
     * @return BelongsToMany<Student>
     */
    public function students(): BelongsToMany {
        return $this->belongsToMany(
            Student::class, 'indigeneity_type_student');
    }

}
