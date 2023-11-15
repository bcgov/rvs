<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];

    public const SUPER_ADMIN = 'Super Admin';

    public const YEAF_USER = 'YEAF User';

    public const YEAF_ADMIN = 'YEAF Admin';

    public const TWP_ADMIN = 'TWP Admin';

    public const TWP_USER = 'TWP User';

    public const VSS_ADMIN = 'VSS Admin';

    public const VSS_USER = 'VSS User';

    public const NEB_ADMIN = 'NEB Admin';

    public const NEB_USER = 'NEB User';

    /**
     * The roles that belong to the user.
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'role_user');
    }
}
