<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $id
 * @property string $institution_code
 * @property string $institution_name
 * @property string $institution_location_code
 * @property string $institution_type_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Vss\Entities\Incident> $incidents
 * @property-read int|null $incidents_count
 * @method static \Illuminate\Database\Eloquent\Builder|Institution newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Institution newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Institution query()
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereInstitutionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereInstitutionLocationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereInstitutionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereInstitutionTypeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereUpdatedAt($value)
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

    public function incidents(): HasMany {
        return $this->hasMany('Modules\Vss\Entities\Incident', 'institution_code', 'institution_code');
    }
}
