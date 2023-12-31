<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CaseNatureOffence extends ModuleModel
{
    use HasFactory;

    protected $primaryKey = null;

    public $incrementing = false;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['incident_id', 'nature_code'];

    public function incident()
    {
        return $this->belongsTo('Modules\Vss\Entities\Incident', 'incident_id', 'incident_id');
    }

    public function offence()
    {
        return $this->belongsTo('Modules\Vss\Entities\NatureOffence', 'nature_code', 'nature_code');
    }
}
