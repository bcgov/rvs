<?php

namespace Modules\Yeaf\Entities;

use Override;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $student_id
 * @property string $comment_text
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string $created_human_date
 */
class Comment extends ModuleModel
{
    use HasFactory, SoftDeletes;

    protected $appends = ['created_human_date'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['student_id', 'comment_text', 'user_id'];

    public function getCreatedHumanDateAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('Y-m-d H:i') : '';
    }
    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[Override]
    protected function casts() : array
    {
        return [
            'date' => 'datetime',
        ];
    }
}
