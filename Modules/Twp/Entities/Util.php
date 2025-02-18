<?php

namespace Modules\Twp\Entities;

use Illuminate\Database\Eloquent\Model;

class Util extends Model
{
    protected $connection = 'twp';

    protected $fillable = ['field_name', 'field_type', 'field_description', 'active_flag'];

    public static function getSortedUtils()
    {
        $utils = self::select('id', 'field_name', 'field_type', 'field_description')
            ->where('active_flag', true)
            ->get();

        $cat_utils = [];
        foreach ($utils as $util) {
            $cat_utils[$util->field_type][] = $util;
        }

        return $cat_utils;
    }
}
