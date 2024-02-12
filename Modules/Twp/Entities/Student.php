<?php

namespace Modules\Twp\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'last_name', 'first_name', 'birth_date', 'email', 'gender', 'pen', 'address', 'citizenship',
        'bc_resident', 'address', 'comment', 'sin', ];

    public function applications()
    {
        return $this->hasMany('Modules\Twp\Entities\Application', 'student_id', 'id');
    }

    public function programs()
    {
        return $this->hasMany('Modules\Twp\Entities\Program', 'student_id', 'id');
    }

    public function application()
    {
        return $this->hasOne('Modules\Twp\Entities\Application', 'student_id', 'id');
    }

    public function indigeneity()
    {
        return $this->belongsToMany(
            'Modules\Twp\Entities\IndigeneityType', 'indigeneity_student', 'student_id',
            'indigeneity_id');
    }

    public function getAgeAttribute()
    {
        if (is_null($this->birth_date)) {
            return null;
        }

        return (new Carbon($this->birth_date))->age;
    }

    public function getAppStatusAttribute()
    {
        return is_null($this->application) ? '' : $this->application->application_status;
    }
}
