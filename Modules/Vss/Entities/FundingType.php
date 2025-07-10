<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int $id
 * @property string $funding_type
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FundingType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FundingType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FundingType query()
 * @method static \Illuminate\Database\Eloquent\Builder|FundingType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FundingType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FundingType whereFundingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FundingType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FundingType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FundingType extends ModuleModel
{
    use HasFactory;

    /**
     * @return HasMany<\Modules\Vss\Entities\CaseFunding>
     */
    public function caseFundings(): HasMany {
        return $this->hasMany('Modules\Vss\Entities\CaseFunding', 'funding_type', 'funding_type');
    }
}
