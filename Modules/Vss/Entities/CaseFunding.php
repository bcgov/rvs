<?php

namespace Modules\Vss\Entities;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_by_user_id
 * @property Carbon|null $deleted_at
 * @property-read FundingType $fundingType
 * @property-read Incident $incident
 * @method static Builder|CaseFunding newModelQuery()
 * @method static Builder|CaseFunding newQuery()
 * @method static Builder|CaseFunding onlyTrashed()
 * @method static Builder|CaseFunding query()
 * @method static Builder|CaseFunding whereApplicationNumber($value)
 * @method static Builder|CaseFunding whereCreatedAt($value)
 * @method static Builder|CaseFunding whereDeletedAt($value)
 * @method static Builder|CaseFunding whereDeletedByUserId($value)
 * @method static Builder|CaseFunding whereFundEntryDate($value)
 * @method static Builder|CaseFunding whereFundingType($value)
 * @method static Builder|CaseFunding whereId($value)
 * @method static Builder|CaseFunding whereIncidentId($value)
 * @method static Builder|CaseFunding whereOverAward($value)
 * @method static Builder|CaseFunding wherePreventedFunding($value)
 * @method static Builder|CaseFunding whereUpdatedAt($value)
 * @method static Builder|CaseFunding withTrashed()
 * @method static Builder|CaseFunding withoutTrashed()
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

    /**
     * @return BelongsTo<Incident, \Modules\Vss\Entities\CaseFunding>
     */
    public function incident(): BelongsTo {
        return $this->belongsTo(Incident::class, 'incident_id', 'incident_id');
    }

    /**
     * @return BelongsTo<FundingType, \Modules\Vss\Entities\CaseFunding>
     */
    public function fundingType(): BelongsTo {
        return $this->belongsTo(FundingType::class, 'funding_type', 'funding_type');
    }
}
