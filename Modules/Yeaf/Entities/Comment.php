<?php

namespace Modules\Yeaf\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
    ];

    public function getCreatedHumanDateAttribute()
    {
        return date('d-M-y', strtotime($this->created_at));
    }
}
