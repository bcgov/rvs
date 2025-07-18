<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @method static Builder|ModuleModel newModelQuery()
 * @method static Builder|ModuleModel newQuery()
 * @method static Builder|ModuleModel query()
 * @mixin \Eloquent
 */
class ModuleModel extends Model
{

    /**
     * @param array<string, mixed> $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = env('DB_DATABASE_VSS');
    }
}
