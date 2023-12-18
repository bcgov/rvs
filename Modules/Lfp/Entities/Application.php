<?php

namespace Modules\Lfp\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function payment()
    {
        return $this->belongsTo('Modules\Lfp\Entities\Lfp', 'lfp_id', 'id');
    }

}
