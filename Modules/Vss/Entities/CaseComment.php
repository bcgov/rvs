<?php

namespace Modules\Vss\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property int $id
 * @property int $incident_id
 * @property string $staff_user_id
 * @property string|null $comment_date
 * @property string|null $comment_text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_by_user_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Modules\Vss\Entities\Incident $incident
 * @method static \Illuminate\Database\Eloquent\Builder|CaseComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseComment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseComment whereCommentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseComment whereCommentText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseComment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseComment whereDeletedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseComment whereIncidentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseComment whereStaffUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseComment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseComment withoutTrashed()
 * @mixin \Eloquent
 */
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
