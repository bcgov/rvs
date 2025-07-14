<?php

namespace Modules\Plsc\Entities;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $sin
 * @property int|null $pen
 * @property int|null $individual_idx
 * @property \Carbon\Carbon|null $birth_date
 * @property string|null $gender
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $address1
 * @property string|null $address2
 * @property string|null $city
 * @property string|null $postal_code
 * @property string|null $province
 * @property string|null $country
 * @property string|null $phone_number
 * @property string|null $email
 * @property string|null $comment
 * @property string|null $citizenship
 * @property string|null $marital_status
 * @property string|null $title
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Application> $applications
 */
class Student extends ModuleModel
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone_number', 'email', 'country', 'province', 'postal_code', 'city', 'address2', 'address1', 'marital_status',
        'citizenship', 'last_name', 'first_name', 'gender', 'pen', 'birth_date', 'sin', 'comment', 'individual_idx', ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Application>>
     */
    public function applications(): HasMany {
        return $this->hasMany('Modules\Plsc\Entities\Application');
    }
}
