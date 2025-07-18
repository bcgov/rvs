<?php

namespace Modules\Vss\Entities;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int $id
 * @property string $referral_code
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Incident> $incidents
 * @property-read int|null $incidents_count
 * @method static Builder|ReferralSource newModelQuery()
 * @method static Builder|ReferralSource newQuery()
 * @method static Builder|ReferralSource query()
 * @method static Builder|ReferralSource whereCreatedAt($value)
 * @method static Builder|ReferralSource whereDescription($value)
 * @method static Builder|ReferralSource whereId($value)
 * @method static Builder|ReferralSource whereReferralCode($value)
 * @method static Builder|ReferralSource whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ReferralSource extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['referral_code', 'description'];

    /**
     * @return HasMany<Incident>
     */
    public function incidents(): HasMany {
        return $this->hasMany(Incident::class, 'referral_source_id', 'id');
    }
}
