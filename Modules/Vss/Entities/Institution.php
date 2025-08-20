<?php

namespace Modules\Vss\Entities;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int $id
 * @property string $institution_code
 * @property string $institution_name
 * @property string $institution_location_code
 * @property string $institution_type_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Incident> $incidents
 * @property-read int|null $incidents_count
 * @method static Builder|Institution newModelQuery()
 * @method static Builder|Institution newQuery()
 * @method static Builder|Institution query()
 * @method static Builder|Institution whereCreatedAt($value)
 * @method static Builder|Institution whereId($value)
 * @method static Builder|Institution whereInstitutionCode($value)
 * @method static Builder|Institution whereInstitutionLocationCode($value)
 * @method static Builder|Institution whereInstitutionName($value)
 * @method static Builder|Institution whereInstitutionTypeCode($value)
 * @method static Builder|Institution whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Institution extends ModuleModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['institution_code', 'institution_name', 'institution_location_code', 'institution_type_code'];

    /**
     * @return hasMany<Incident>
     */
    public function incidents(): HasMany {
        return $this->hasMany(Incident::class, 'institution_code', 'institution_code');
    }
}
