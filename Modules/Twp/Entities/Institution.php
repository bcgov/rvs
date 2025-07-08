<?php

namespace Modules\Twp\Entities;


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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Program>
     */
    public function programs(): HasMany {
        return $this->hasMany('Modules\Twp\Entities\Program', 'institution_twp_id', 'id');
    }
}
