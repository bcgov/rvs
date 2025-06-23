<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class FundingType extends ModuleModel
{
    use HasFactory;

    public function caseFundings(): HasMany {
        return $this->hasMany('Modules\Vss\Entities\CaseFunding', 'funding_type', 'funding_type');
    }
}
