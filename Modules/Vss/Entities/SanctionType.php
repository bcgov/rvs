<?php

namespace Modules\Vss\Entities;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, CaseSanctionType> $sanctions
 * @property-read int|null $sanctions_count
 * @method static Builder|SanctionType newModelQuery()
 * @method static Builder|SanctionType newQuery()
 * @method static Builder|SanctionType query()
 * @method static Builder|SanctionType whereCreatedAt($value)
 * @method static Builder|SanctionType whereDescription($value)
 * @method static Builder|SanctionType whereDisabled($value)
 * @method static Builder|SanctionType whereId($value)
 * @method static Builder|SanctionType whereSanctionCode($value)
 * @method static Builder|SanctionType whereShortDescription($value)
 * @method static Builder|SanctionType whereUpdatedAt($value)
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
        return $this->hasMany(CaseSanctionType::class, 'incident_id', 'incident_id');
    }
}
