<?php

namespace Modules\Twp\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Student, Grant>
     */
    public function student(): BelongsTo {
        return $this->belongsTo('Modules\Twp\Entities\Student', 'student_id', 'id');
    }
}
