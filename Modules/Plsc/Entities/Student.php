<?php

namespace Modules\Plsc\Entities;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applications(): HasMany {
        return $this->hasMany('Modules\Plsc\Entities\Application');
    }
}
