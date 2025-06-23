<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaseComment extends ModuleModel
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['incident_id', 'staff_user_id', 'comment_date', 'comment_text', 'deleted_by_user_id'];

    public function incident(): BelongsTo {
        return $this->belongsTo('Modules\Vss\Entities\Incident', 'incident_id', 'incident_id');
    }
}
