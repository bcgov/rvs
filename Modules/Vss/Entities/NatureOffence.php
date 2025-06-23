<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NatureOffence extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nature_code', 'description'];

    public function offences(): HasMany {
        return $this->hasMany('Modules\Vss\Entities\CaseNatureOffence', 'nature_code', 'nature_code');
    }
}
