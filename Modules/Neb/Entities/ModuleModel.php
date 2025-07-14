<?php

namespace Modules\Neb\Entities;

use Illuminate\Database\Eloquent\Model;

class ModuleModel extends Model
{

    /**
     * @param array $attributes<string, mixed>
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = env('DB_DATABASE_NEB');
    }
}
