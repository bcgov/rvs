<?php

namespace Modules\Yeaf\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrantIneligible extends ModuleModel
{
    use HasFactory;

    public function ineligible(): BelongsTo {
        return $this->belongsTo('Modules\Yeaf\Entities\Ineligible', 'ineligible_code_id', 'code_id');
    }
}
