<?php

namespace Modules\Yeaf\Entities;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $code_id
 * @property bool|int $active_flag
 * @property string $code_type
 * @property string $description
 * @property string|null $paragraph_text
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Ineligible extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['code_id', 'active_flag', 'code_type', 'description', 'paragraph_text'];
}
