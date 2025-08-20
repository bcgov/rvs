<?php

namespace Modules\Twp\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 * @property int $id
 * @property int $student_id
 * @property string $received_date
 * @property string $grant_status
 * @property float $grant_amount
 * @property string|null $grant_comments
 * @property int|null $application_id
 * @property string|null $created_by
 * @property string|null $updated_by
 */
class Grant extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['student_id', 'received_date', 'grant_status', 'grant_amount', 'grant_comments', 'application_id',
        'created_by', 'updated_by', ];

    /**
     * @return BelongsTo<Student, Grant>
     */
    public function student(): BelongsTo {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
