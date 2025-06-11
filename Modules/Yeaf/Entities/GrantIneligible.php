<?php

namespace Modules\Yeaf\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class GrantIneligible
 *
 * @property int $id
 * @property int $grant_id
 * @property int $ineligible_code_id
 * @property string|null $notes
 * @property int|null $created_by
 * @property bool|null $cleared_flag
 * @property string|null $ineligible_code_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Ineligible $ineligible
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

    public function ineligible(): BelongsTo {
        return $this->belongsTo('Modules\Yeaf\Entities\Ineligible', 'ineligible_code_id', 'code_id');
    }
}
