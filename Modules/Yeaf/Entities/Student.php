<?php

namespace Modules\Yeaf\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $student_id
 * @property string $sin
 * @property string $last_name
 * @property string $first_name
 * @property string $address
 * @property string $city
 * @property string $birth_date
 * @property string $country
 * @property string $province
 * @property string $postal_code
 * @property string $tele
 * @property string $email
 * @property string $gender
 * @property string $pen
 * @property string $institution_student_number
 * @property bool $overaward_flag
 * @property bool $investigate
 * @property bool $pd
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Student extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id', 'sin', 'last_name', 'first_name', 'address', 'city', 'birth_date', 'country', 'province',
        'postal_code', 'tele', 'email', 'gender', 'pen', 'institution_student_number', 'overaward_flag', 'investigate', 'pd', ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function grants(): HasMany {
        return $this->hasMany('Modules\Yeaf\Entities\Grant', 'student_id', 'student_id')->orderBy('grant_id');
    }

    public function comments(): HasMany {
        return $this->hasMany('Modules\Yeaf\Entities\Comment', 'student_id', 'student_id');
    }

    public function appeals(): HasMany {
        return $this->hasMany('Modules\Yeaf\Entities\Appeal', 'student_id', 'student_id');
    }
}
