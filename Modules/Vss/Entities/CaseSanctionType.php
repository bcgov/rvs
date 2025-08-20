<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $incident_id
 * @property int $sanction_code
 * @property-read Incident $incident
 * @property-read SanctionType $sanction
 * @method static Builder|CaseSanctionType newModelQuery()
 * @method static Builder|CaseSanctionType newQuery()
 * @method static Builder|CaseSanctionType query()
 * @method static Builder|CaseSanctionType whereIncidentId($value)
 * @method static Builder|CaseSanctionType whereSanctionCode($value)
 * @mixin \Eloquent
 */
class CaseSanctionType extends ModuleModel
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
    protected $fillable = ['incident_id', 'sanction_code'];

    /**
     * @return BelongsTo<Incident, \Modules\Vss\Entities\CaseSanctionType>
     */
    public function incident(): BelongsTo {
        return $this->belongsTo(Incident::class, 'incident_id', 'incident_id');
    }

    /**
     * @return BelongsTo<SanctionType, \Modules\Vss\Entities\CaseSanctionType>
     */
    public function sanction(): BelongsTo {
        return $this->belongsTo(SanctionType::class, 'sanction_code', 'sanction_code');
    }
}
