<?php

namespace Modules\Plsc\Entities;

use Illuminate\Database\Eloquent\Model;

class ModuleModel extends Model
{

    /**
     * @param array<int, mixed> $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = env('DB_DATABASE_PLSC');
    }
}
