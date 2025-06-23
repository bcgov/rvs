<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaseFunding extends ModuleModel
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['incident_id', 'application_number', 'funding_type', 'fund_entry_date', 'over_award', 'prevented_funding', 'deleted_by_user_id'];

    public function incident(): BelongsTo {
        return $this->belongsTo('Modules\Vss\Entities\Incident', 'incident_id', 'incident_id');
    }

    public function fundingType(): BelongsTo {
        return $this->belongsTo('Modules\Vss\Entities\FundingType', 'funding_type', 'funding_type');
    }
}
