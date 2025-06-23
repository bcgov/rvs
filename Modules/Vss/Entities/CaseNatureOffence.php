<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $incident_id
 * @property string $nature_code
 * @property-read \Modules\Vss\Entities\Incident $incident
 * @property-read \Modules\Vss\Entities\NatureOffence $offence
 * @method static \Illuminate\Database\Eloquent\Builder|CaseNatureOffence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseNatureOffence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseNatureOffence query()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseNatureOffence whereIncidentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseNatureOffence whereNatureCode($value)
 * @mixin \Eloquent
 */
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

    public function incident(): BelongsTo {
        return $this->belongsTo('Modules\Vss\Entities\Incident', 'incident_id', 'incident_id');
    }

    public function offence(): BelongsTo {
        return $this->belongsTo('Modules\Vss\Entities\NatureOffence', 'nature_code', 'nature_code');
    }
}
