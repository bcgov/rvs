<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $id
 * @property string $nature_code
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Vss\Entities\CaseNatureOffence> $offences
 * @property-read int|null $offences_count
 * @method static \Illuminate\Database\Eloquent\Builder|NatureOffence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NatureOffence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NatureOffence query()
 * @method static \Illuminate\Database\Eloquent\Builder|NatureOffence whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NatureOffence whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NatureOffence whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NatureOffence whereNatureCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NatureOffence whereUpdatedAt($value)
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

    public function offences(): HasMany {
        return $this->hasMany('Modules\Vss\Entities\CaseNatureOffence', 'nature_code', 'nature_code');
    }
}
