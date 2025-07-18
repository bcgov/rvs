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
 * @property string $nature_code
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, CaseNatureOffence> $offences
 * @property-read int|null $offences_count
 * @method static Builder|NatureOffence newModelQuery()
 * @method static Builder|NatureOffence newQuery()
 * @method static Builder|NatureOffence query()
 * @method static Builder|NatureOffence whereCreatedAt($value)
 * @method static Builder|NatureOffence whereDescription($value)
 * @method static Builder|NatureOffence whereId($value)
 * @method static Builder|NatureOffence whereNatureCode($value)
 * @method static Builder|NatureOffence whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class NatureOffence extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nature_code', 'description'];

    /**
     * @return HasMany<CaseNatureOffence>
     */
    public function offences(): HasMany {
        return $this->hasMany(CaseNatureOffence::class, 'nature_code', 'nature_code');
    }
}
