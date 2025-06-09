<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public const YEAF_ADMIN = 'YEAF Admin';
    public const TWP_ADMIN = 'TWP Admin';
    public const VSS_ADMIN = 'VSS Admin';
    public const NEB_ADMIN = 'NEB Admin';
    public const LFP_ADMIN = 'LFP Admin';
    public const PLSC_ADMIN = 'PLSC Admin';

    public const YEAF_USER = 'YEAF User';
    public const TWP_USER = 'TWP User';
    public const VSS_USER = 'VSS User';
    public const NEB_USER = 'NEB User';
    public const LFP_USER = 'LFP User';
    public const PLSC_USER = 'PLSC User';

    public const YEAF_GUEST = 'YEAF Guest';
    public const TWP_GUEST = 'TWP Guest';
    public const VSS_GUEST = 'VSS Guest';
    public const NEB_GUEST = 'NEB Guest';
    public const LFP_GUEST = 'LFP Guest';
    public const PLSC_GUEST = 'PLSC Guest';



    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = "pgsql";
    }

    /**
     * The roles that belong to the user.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\User', 'role_user');
    }
}
