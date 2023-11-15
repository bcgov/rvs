<?php

namespace Modules\Yeaf\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Batch extends ModuleModel
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'batch_number', 'batch_date', ];

    protected $appends = ['batch_human_date'];

    public function getBatchHumanDateAttribute()
    {
        $date = date('F Y', strtotime($this->batch_date));

        return Str::endsWith($this->batch_date, '15') ? $date.' - Mid' : $date.' - End';
    }
}
