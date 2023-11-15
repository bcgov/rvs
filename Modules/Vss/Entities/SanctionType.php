<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SanctionType extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['sanction_code', 'description', 'short_description', 'disabled'];

    public function sanctions()
    {
        return $this->hasMany('Modules\Vss\Entities\CaseSanctionType', 'incident_id', 'incident_id');
    }
}
