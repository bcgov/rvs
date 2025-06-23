<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property int $id
 * @property int $incident_id
 * @property float|null $application_number
 * @property string $funding_type
 * @property float $over_award
 * @property float $prevented_funding
 * @property string|null $fund_entry_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_by_user_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Modules\Vss\Entities\FundingType $fundingType
 * @property-read \Modules\Vss\Entities\Incident $incident
 * @method static \Illuminate\Database\Eloquent\Builder|CaseFunding newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseFunding newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseFunding onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseFunding query()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseFunding whereApplicationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseFunding whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseFunding whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseFunding whereDeletedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseFunding whereFundEntryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseFunding whereFundingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseFunding whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseFunding whereIncidentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseFunding whereOverAward($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseFunding wherePreventedFunding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseFunding whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseFunding withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseFunding withoutTrashed()
 * @mixin \Eloquent
 */
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
