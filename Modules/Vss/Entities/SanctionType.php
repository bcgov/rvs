<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int $id
 * @property int $sanction_code
 * @property string $description
 * @property string $short_description
 * @property bool $disabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Vss\Entities\CaseSanctionType> $sanctions
 * @property-read int|null $sanctions_count
 * @method static \Illuminate\Database\Eloquent\Builder|SanctionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SanctionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SanctionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|SanctionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SanctionType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SanctionType whereDisabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SanctionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SanctionType whereSanctionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SanctionType whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SanctionType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SanctionType extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['sanction_code', 'description', 'short_description', 'disabled'];

    /**
     * @return HasMany<CaseSanctionType>
     */
    public function sanctions(): HasMany {
        return $this->hasMany('Modules\Vss\Entities\CaseSanctionType', 'incident_id', 'incident_id');
    }
}
