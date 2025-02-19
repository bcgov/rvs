<?php

namespace Database\Seeders\ModelsSeeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::factory()->count(10)->create()->each(function ($user) {
            $role = Role::where('id', '>=', 6)->where('id', '<=', 13)->inRandomOrder()->first();
            $user->roles()->attach($role->id);
        });
    }
}
