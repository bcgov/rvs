<?php

namespace Modules\Yeaf\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class GrantIneligible extends ModuleModel
{
    use HasFactory;

    public function ineligible()
    {
        return $this->belongsTo('Modules\Yeaf\Entities\Ineligible', 'ineligible_code_id', 'code_id');
    }
}
