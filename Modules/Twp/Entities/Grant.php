<?php

namespace Modules\Twp\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function student()
    {
        return $this->belongsTo('Modules\Twp\Entities\Student', 'student_id', 'id');
    }
}
