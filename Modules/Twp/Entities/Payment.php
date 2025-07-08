<?php

namespace Modules\Twp\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 * @property int $id
 * @property int $student_id
 * @property int $program_id
 * @property int $application_id
 * @property string $payment_date
 * @property float $payment_amount
 * @property int $payment_type_id
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class Payment extends ModuleModel
{
    use HasFactory;
    use SoftDeletes;

    protected $appends = ['payment_type_readable'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['student_id', 'program_id', 'payment_type_id', 'payment_date', 'payment_amount', 'application_id', 'comment'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Student, Payment>
     */
    public function student(): BelongsTo {
        return $this->belongsTo('Modules\Twp\Entities\Student', 'student_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<PaymentType>
     */
    public function paymentType(): HasOne {
        return $this->hasOne('Modules\Twp\Entities\PaymentType', 'id', 'payment_type_id');
    }

    /**
     * @return string
     */
    public function getPaymentTypeReadableAttribute(): string {
        return is_null($this->paymentType) ? '' : $this->paymentType->title;
    }
}
