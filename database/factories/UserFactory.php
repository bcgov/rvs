<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->unique()->userName,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'disabled' => $this->faker->boolean,
            'tele' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password,
            'idir_user_guid' => $this->faker->uuid,
            'remember_token' => Str::random(10),
            'deleted_at' => $this->faker->optional()->dateTimeThisYear,
            'created_at' => $this->faker->dateTimeThisYear,
            'updated_at' => $this->faker->dateTimeThisYear,
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->optional()->date(),
        ];
    }
}
