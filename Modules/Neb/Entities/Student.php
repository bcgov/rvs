<?php

namespace Modules\Neb\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends ModuleModel
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone_number', 'email', 'country', 'province', 'postal_code', 'city', 'address2', 'address1', 'marital_status',
        'citizenship', 'old_last_name', 'old_middle_name', 'old_first_name', 'last_name', 'middle_name', 'first_name',
        'gender', 'pen', 'date_of_birth', 'sin', ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Application>
     */
    public function applications(): HasMany {
        return $this->hasMany('Modules\Neb\Entities\Application', 'sin', 'sin');
    }
}
