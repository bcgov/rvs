<?php

namespace Modules\Lfp\Entities;

use Illuminate\Database\Eloquent\Model;

class Intake extends ModuleModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sin', 'first_name', 'last_name', 'profession', 'employer', 'employment_status', 'community', 'repayment_status',
        'in_good_standing', 'repayment_start_date', 'amount_owing', 'intake_status', 'receive_date', 'comment',
        'proposed_registration_date', 'denial_reason',
    ];

}
