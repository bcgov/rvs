<?php

namespace Modules\Yeaf\Entities;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
        return $this->belongsTo('Modules\Yeaf\Entities\Ineligible', 'ineligible_code_id', 'code_id');
    }
}
