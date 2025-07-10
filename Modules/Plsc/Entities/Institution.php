<?php

namespace Modules\Plsc\Entities;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends ModuleModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'contact_name', 'contact_email', 'active_flag'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Application>
     */
    public function applications(): HasMany {
        return $this->hasMany('Modules\Plsc\Entities\Application');
    }
}
