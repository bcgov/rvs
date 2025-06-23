<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SanctionType extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['sanction_code', 'description', 'short_description', 'disabled'];

    public function sanctions(): HasMany {
        return $this->hasMany('Modules\Vss\Entities\CaseSanctionType', 'incident_id', 'incident_id');
    }
}
