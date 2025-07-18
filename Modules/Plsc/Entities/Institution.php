<?php

namespace Modules\Plsc\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string|null $contact_name
 * @property string|null $contact_email
 * @property bool $active_flag
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<int, Application> $applications
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
     * @return HasMany<Application>
     */
    public function applications(): HasMany {
        return $this->hasMany(Application::class);
    }
}
