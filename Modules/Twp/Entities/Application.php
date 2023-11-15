<?php

namespace Modules\Twp\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['student_id', 'received_date', 'application_status', 'twp_status', 'denial_reason', 'exception_comments',
        'institution_student_number', 'apply_twp', 'apply_lfg', 'confirmation_enrolment', 'sabc_app_number',
        'fao_name', 'fao_email', 'fao_phone', ];

    public function reasons()
    {
        return $this->belongsToMany(
            'Modules\Twp\Entities\Reason', // The model to access to
            'Modules\Twp\Entities\ApplicationReason', // The intermediate table that connects the Application with the Reason.
            'application_id',                 // The column of the intermediate table that connects to this model by its ID.
            'reason_id',              // The column of the intermediate table that connects the Reason by its ID.
            'id',                      // The column that connects this model with the intermediate model table.
            'id'               // The column of the Reason table that ties it to the Application.
        );
    }

    public function student()
    {
        return $this->belongsTo('Modules\Twp\Entities\Student', 'student_id', 'id');
    }

    public function program()
    {
        return $this->hasOne('Modules\Twp\Entities\Program', 'application_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany('Modules\Twp\Entities\Payment', 'application_id', 'id');
    }

    public function grants()
    {
        return $this->hasMany('Modules\Twp\Entities\Grant', 'application_id', 'id')->orderByDesc('created_at');
    }
}
