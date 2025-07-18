<?php

namespace Modules\Vss\Entities;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int $id
 * @property string $funding_type
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|FundingType newModelQuery()
 * @method static Builder|FundingType newQuery()
 * @method static Builder|FundingType query()
 * @method static Builder|FundingType whereCreatedAt($value)
 * @method static Builder|FundingType whereDescription($value)
 * @method static Builder|FundingType whereFundingType($value)
 * @method static Builder|FundingType whereId($value)
 * @method static Builder|FundingType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FundingType extends ModuleModel
{
    use HasFactory;

    /**
     * @return HasMany<CaseFunding>
     */
    public function caseFundings(): HasMany {
        return $this->hasMany(CaseFunding::class, 'funding_type', 'funding_type');
    }
}
