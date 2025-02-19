<?php

namespace Database\Factories;

use App\Models\Ministry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ministry>
 */
class MinistryFactory extends Factory
{
    protected $model = Ministry::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Ministry',
            'branch' => $this->faker->companySuffix,
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'prov' => $this->faker->stateAbbr,
            'postal' => $this->faker->postcode,
            'tele_victoria' => $this->faker->phoneNumber,
            'tele_lower' => $this->faker->phoneNumber,
            'tele_toll_free' => $this->faker->tollFreePhoneNumber,
            'TTY_line' => $this->faker->phoneNumber,
            'location' => $this->faker->secondaryAddress,
            'location_city' => $this->faker->city,
            'location_prov' => $this->faker->stateAbbr,
            'location_postal' => $this->faker->postcode,
            'location_tele_toll_free' => $this->faker->tollFreePhoneNumber,
            'fax' => $this->faker->phoneNumber,
        ];
    }
}
