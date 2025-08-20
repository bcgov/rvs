<?php

namespace Database\Factories;

use Override;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    #[Override]
    public function definition()
    {
        return [
            'user_id' => fake()->unique()->userName,
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'disabled' => fake()->boolean,
            'tele' => fake()->phoneNumber,
            'email' => fake()->unique()->safeEmail,
            'password' => fake()->password,
            'idir_user_guid' => fake()->uuid,
            'remember_token' => Str::random(10),
            'deleted_at' => fake()->optional()->dateTimeThisYear,
            'created_at' => fake()->dateTimeThisYear,
            'updated_at' => fake()->dateTimeThisYear,
            'start_date' => fake()->date(),
            'end_date' => fake()->optional()->date(),
        ];
    }
}
