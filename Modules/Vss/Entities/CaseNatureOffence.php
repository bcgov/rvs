<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $incident_id
 * @property string $nature_code
 * @property-read Incident $incident
 * @property-read NatureOffence $offence
 * @method static Builder|CaseNatureOffence newModelQuery()
 * @method static Builder|CaseNatureOffence newQuery()
 * @method static Builder|CaseNatureOffence query()
 * @method static Builder|CaseNatureOffence whereIncidentId($value)
 * @method static Builder|CaseNatureOffence whereNatureCode($value)
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

    /**
     * @return BelongsTo<Incident, \Modules\Vss\Entities\CaseNatureOffence>
     */
    public function incident(): BelongsTo {
        return $this->belongsTo(Incident::class, 'incident_id', 'incident_id');
    }

    /**
     * @return BelongsTo<NatureOffence, \Modules\Vss\Entities\CaseNatureOffence>
     */
    public function offence(): BelongsTo {
        return $this->belongsTo(NatureOffence::class, 'nature_code', 'nature_code');
    }
}
