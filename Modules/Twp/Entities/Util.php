<?php

namespace Modules\Twp\Entities;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @property int $id
 * @property string $field_name
 * @property string $field_type
 * @property string|null $field_description
 * @property bool $active_flag
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Util extends Model
{
    protected $connection = 'twp';

    protected $fillable = ['field_name', 'field_type', 'field_description', 'active_flag'];

    /**
     * @return array<array<int, Util>>
     */
    public static function getSortedUtils(): array {
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
