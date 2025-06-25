<?php

namespace Modules\Yeaf\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $institution_id
 * @property string $name
 * @property string $address
 * @property string $city
 * @property string|null $country
 * @property string|null $province
 * @property string|null $postal_code
 * @property string|null $tele
 * @property string|null $fax
 */
class Institution extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['institution_id', 'name', 'address', 'city', 'country', 'province', 'postal_code', 'tele', 'fax'];
}
