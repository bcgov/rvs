<?php

namespace Modules\Neb\Entities;

use Illuminate\Database\Eloquent\Model;

class ModuleModel extends Model
{

    /**
     * @param array<string, mixed> $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = env('DB_DATABASE_NEB');
    }
}
