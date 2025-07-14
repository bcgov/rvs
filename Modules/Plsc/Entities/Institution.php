<?php

namespace Modules\Plsc\Entities;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string|null $contact_name
 * @property string|null $contact_email
 * @property bool $active_flag
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Application> $applications
 */
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
