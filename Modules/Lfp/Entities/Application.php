<?php

namespace Modules\Lfp\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lfp_id', 'application_number', ];

    /**
     * @return BelongsTo<Lfp, Application>
     */
    public function payment(): BelongsTo {
        return $this->belongsTo(Lfp::class, 'lfp_id', 'id');
    }

}
