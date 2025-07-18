<?php

namespace Modules\Vss\Entities;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_by_user_id
 * @property Carbon|null $deleted_at
 * @property-read Incident $incident
 * @method static Builder|CaseComment newModelQuery()
 * @method static Builder|CaseComment newQuery()
 * @method static Builder|CaseComment onlyTrashed()
 * @method static Builder|CaseComment query()
 * @method static Builder|CaseComment whereCommentDate($value)
 * @method static Builder|CaseComment whereCommentText($value)
 * @method static Builder|CaseComment whereCreatedAt($value)
 * @method static Builder|CaseComment whereDeletedAt($value)
 * @method static Builder|CaseComment whereDeletedByUserId($value)
 * @method static Builder|CaseComment whereId($value)
 * @method static Builder|CaseComment whereIncidentId($value)
 * @method static Builder|CaseComment whereStaffUserId($value)
 * @method static Builder|CaseComment whereUpdatedAt($value)
 * @method static Builder|CaseComment withTrashed()
 * @method static Builder|CaseComment withoutTrashed()
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

    /**
     * @return BelongsTo<Incident, \Modules\Vss\Entities\CaseComment>
     */
    public function incident(): BelongsTo {
        return $this->belongsTo(Incident::class, 'incident_id', 'incident_id');
    }
}
