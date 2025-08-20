<?php

namespace Modules\Yeaf\Entities;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class GrantIneligible
 *
 * @property int $id
 * @property int|null $grant_id
 * @property string|null $ineligible_code_id
 * @property string $created_by
 * @property bool $cleared_flag
 * @property string|null $ineligible_code_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Grant|null $grant
 * @property-read Ineligible|null $ineligible
 *
 * @package Modules\Yeaf\Entities
 */
class GrantIneligible extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['grant_id', 'ineligible_code_id', 'created_by', 'cleared_flag', 'ineligible_code_type', 'created_at', 'updated_at'];

    /**
     * @return BelongsTo<Ineligible, GrantIneligible>
     */
    public function ineligible(): BelongsTo {
        return $this->belongsTo(Ineligible::class, 'ineligible_code_id', 'code_id');
    }
}
