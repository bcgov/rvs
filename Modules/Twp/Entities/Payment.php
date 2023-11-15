<?php

namespace Modules\Twp\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends ModuleModel
{
    use HasFactory;

    protected $appends = ['payment_type_readable'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['student_id', 'program_id', 'payment_type_id', 'payment_date', 'payment_amount', 'application_id'];

    public function student()
    {
        return $this->belongsTo('Modules\Twp\Entities\Student', 'student_id', 'id');
    }

    public function paymentType()
    {
        return $this->hasOne('Modules\Twp\Entities\PaymentType', 'id', 'payment_type_id');
    }

    public function getPaymentTypeReadableAttribute()
    {
        return is_null($this->paymentType) ? '' : $this->paymentType->title;
    }
}
