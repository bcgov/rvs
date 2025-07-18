<?php

namespace Modules\Twp\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string|null $last_name
 * @property string|null $first_name
 * @property string|null $alias_name
 * @property string|null $birth_date
 * @property string|null $email
 * @property string|null $gender
 * @property string|null $pen
 * @property string|null $address
 * @property string|null $citizenship
 * @property bool $bc_resident
 * @property string|null $comment
 * @property string|null $sin
 */
class Student extends ModuleModel
{
    use HasFactory;
    use SoftDeletes;

    protected $appends = ['age', 'app_status'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'last_name', 'first_name', 'alias_name', 'birth_date', 'email', 'gender', 'pen', 'address', 'citizenship',
        'bc_resident', 'address', 'comment', 'sin', ];

    /**
     * @return HasMany<Application>
     */
    public function applications(): HasMany {
        return $this->hasMany(Application::class, 'student_id', 'id');
    }

    /**
     * @return HasMany<Program>
     */
    public function programs(): HasMany {
        return $this->hasMany(Program::class, 'student_id', 'id');
    }

    /**
     * @return HasOne<Application>
     */
    public function application(): HasOne {
        return $this->hasOne(Application::class, 'student_id', 'id');
    }

    /**
     * @return BelongsToMany<IndigeneityType>
     */
    public function indigeneity(): BelongsToMany {
        return $this->belongsToMany(
            IndigeneityType::class, 'indigeneity_type_student');
    }

    /**
     * @return int|null
     */
    public function getAgeAttribute(): ?int {
        if (is_null($this->birth_date)) {
            return null;
        }

        return (new Carbon($this->birth_date))->age;
    }

    /**
     * @return string
     */
    public function getAppStatusAttribute(): string {
        return is_null($this->application) ? '' : $this->application->application_status;
    }
}
