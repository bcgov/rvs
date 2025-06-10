<?php

namespace Modules\Yeaf\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grant extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['student_id', 'grant_id', 'institution_id', 'application_receive_date', 'program_code', 'program_year_id',
        'study_start_date', 'study_end_date', 'age', 'officer_user_id', 'creator_user_id', 'update_user_id', 'status_code',
        'last_evaluation_date', 'application_type', 'program_name', 'application_number', 'program_other_description',
    ];

    protected $appends = ['formSubmitting'];

    public function student(): BelongsTo {
        return $this->belongsTo('Modules\Yeaf\Entities\Student', 'student_id', 'student_id');
    }

    public function batch(): BelongsTo {
        return $this->belongsTo('Modules\Yeaf\Entities\Batch', 'cheque_batch_number', 'batch_number');
    }

    public function officer(): BelongsTo {
        $userModel = new User;

        return $this->belongsTo($userModel, 'officer_user_id', 'user_id');
    }

    public function py(): BelongsTo {
        return $this->belongsTo('Modules\Yeaf\Entities\ProgramYear', 'program_year_id', 'program_year_id');
    }

    public function school(): BelongsTo {
        return $this->belongsTo('Modules\Yeaf\Entities\Institution', 'institution_id', 'institution_id');
    }

    public function appeals(): HasMany {
        return $this->hasMany('Modules\Yeaf\Entities\Appeal', 'grant_id', 'grant_id');
    }

    public function grantIneligibles(): HasMany {
        return $this->hasMany('Modules\Yeaf\Entities\GrantIneligible', 'grant_id', 'grant_id');
    }

    public function grantPendingIneligibles(): HasMany {
        return $this->hasMany('Modules\Yeaf\Entities\GrantIneligible', 'grant_id', 'grant_id')->where('ineligible_code_type', 'P');
    }

    public function grantDeniedIneligibles(): HasMany {
        return $this->hasMany('Modules\Yeaf\Entities\GrantIneligible', 'grant_id', 'grant_id')->where('ineligible_code_type', 'D');
    }

    public function getFormSubmittingAttribute(): false {
        return false;
    }
}
