<?php

namespace Modules\Yeaf\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $appeal_id
 * @property string $student_id
 * @property int|null $grant_id
 * @property int|null $program_year_id
 * @property string|null $adjudicated_by_user_id
 * @property string|null $updated_by_user_id
 * @property string $appeal_code
 * @property string|null $appeal_date
 * @property string|null $status_code
 * @property string|null $status_affective_date
 * @property string|null $other_appeal_explain
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */

class Appeal extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'appeal_id',
        'student_id',
        'grant_id',
        'program_year_id',
        'adjudicated_by_user_id',
        'updated_by_user_id',
        'appeal_code',
        'appeal_date',
        'status_code',
        'status_affective_date',
        'other_appeal_explain',
    ];
}
