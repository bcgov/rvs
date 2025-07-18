<?php

namespace Modules\Twp\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $student_id
 * @property string $received_date
 * @property string $application_status
 * @property string|null $twp_status
 * @property string|null $denial_reason
 * @property string|null $exception_comments
 * @property string|null $institution_student_number
 * @property bool $apply_twp
 * @property bool $apply_lfg
 * @property string|null $confirmation_enrolment
 * @property string|null $sabc_app_number
 * @property string|null $fao_name
 * @property string|null $fao_email
 * @property string|null $fao_phone
 * @property string|null $comment
 * @property string|null $created_by
 * @property string|null $updated_by
 */
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
     * @return BelongsToMany<Reason>
     */
    public function reasons(): BelongsToMany {
        return $this->belongsToMany(
            Reason::class, // The model to access to
            ApplicationReason::class, // The intermediate table that connects the Application with the Reason.
            'application_id',                 // The column of the intermediate table that connects to this model by its ID.
            'reason_id',              // The column of the intermediate table that connects the Reason by its ID.
            'id',                      // The column that connects this model with the intermediate model table.
            'id'               // The column of the Reason table that ties it to the Application.
        );
    }

    /**
     * @return BelongsTo<Student, Application>
     */
    public function student(): BelongsTo {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    /**
     * @return HasOne<Program>
     */
    public function program(): HasOne {
        return $this->hasOne(Program::class, 'application_id', 'id');
    }

    /**
     * @return HasMany<Payment>
     */
    public function payments(): HasMany {
        return $this->hasMany(Payment::class, 'application_id', 'id');
    }

    /**
     * @return HasMany<Grant>
     */
    public function grants(): HasMany {
        return $this->hasMany(Grant::class, 'application_id', 'id')->orderByDesc('created_at');
    }
}
