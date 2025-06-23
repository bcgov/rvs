<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReferralSource extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['referral_code', 'description'];

    public function incidents(): HasMany {
        return $this->hasMany('Modules\Vss\Entities\Incident', 'referral_source_id', 'id');
    }
}
