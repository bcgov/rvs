<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $incident_id
 * @property int $sanction_code
 * @property-read \Modules\Vss\Entities\Incident $incident
 * @property-read \Modules\Vss\Entities\SanctionType $sanction
 * @method static \Illuminate\Database\Eloquent\Builder|CaseSanctionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseSanctionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseSanctionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseSanctionType whereIncidentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseSanctionType whereSanctionCode($value)
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

    public function incident(): BelongsTo {
        return $this->belongsTo('Modules\Vss\Entities\Incident', 'incident_id', 'incident_id');
    }

    public function sanction(): BelongsTo {
        return $this->belongsTo('Modules\Vss\Entities\SanctionType', 'sanction_code', 'sanction_code');
    }
}
