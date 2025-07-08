<?php

namespace Modules\Twp\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends ModuleModel
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['student_id', 'received_date', 'application_status', 'twp_status', 'denial_reason', 'exception_comments',
        'institution_student_number', 'apply_twp', 'apply_lfg', 'confirmation_enrolment', 'sabc_app_number',
        'fao_name', 'fao_email', 'fao_phone', 'comment'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Reason>
     */
    public function reasons(): BelongsToMany {
        return $this->belongsToMany(
            'Modules\Twp\Entities\Reason', // The model to access to
            'Modules\Twp\Entities\ApplicationReason', // The intermediate table that connects the Application with the Reason.
            'application_id',                 // The column of the intermediate table that connects to this model by its ID.
            'reason_id',              // The column of the intermediate table that connects the Reason by its ID.
            'id',                      // The column that connects this model with the intermediate model table.
            'id'               // The column of the Reason table that ties it to the Application.
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Student, Application>
     */
    public function student(): BelongsTo {
        return $this->belongsTo('Modules\Twp\Entities\Student', 'student_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<Program>
     */
    public function program(): HasOne {
        return $this->hasOne('Modules\Twp\Entities\Program', 'application_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Payment>
     */
    public function payments(): HasMany {
        return $this->hasMany('Modules\Twp\Entities\Payment', 'application_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Grant>
     */
    public function grants(): HasMany {
        return $this->hasMany('Modules\Twp\Entities\Grant', 'application_id', 'id')->orderByDesc('created_at');
    }
}
