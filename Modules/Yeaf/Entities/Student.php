<?php

namespace Modules\Yeaf\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
