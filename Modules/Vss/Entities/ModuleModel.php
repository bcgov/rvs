<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleModel query()
 * @mixin \Eloquent
 */
class ModuleModel extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = env('DB_DATABASE_VSS');
    }
}
