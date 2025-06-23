<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $id
 * @property string $referral_code
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Vss\Entities\Incident> $incidents
 * @property-read int|null $incidents_count
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralSource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralSource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralSource query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralSource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralSource whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralSource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralSource whereReferralCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralSource whereUpdatedAt($value)
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

    public function incidents(): HasMany {
        return $this->hasMany('Modules\Vss\Entities\Incident', 'referral_source_id', 'id');
    }
}
