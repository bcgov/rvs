<?php

namespace Modules\Lfp\Entities;

use Illuminate\Database\Eloquent\Model;

class ModuleModel extends Model
{

    /**
     * @param array<string> $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = env('DB_DATABASE_LFP');
    }
}
