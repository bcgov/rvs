<?php

namespace Database\Factories;

use Override;
use App\Models\Ministry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ministry>
 */
class MinistryFactory extends Factory
{
    protected $model = Ministry::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function definition(): array
    {
        return [
            'name' => fake()->company . ' Ministry',
            'branch' => fake()->companySuffix,
            'address' => fake()->streetAddress,
            'city' => fake()->city,
            'prov' => fake()->stateAbbr,
            'postal' => fake()->postcode,
            'tele_victoria' => fake()->phoneNumber,
            'tele_lower' => fake()->phoneNumber,
            'tele_toll_free' => fake()->tollFreePhoneNumber,
            'TTY_line' => fake()->phoneNumber,
            'location' => fake()->secondaryAddress,
            'location_city' => fake()->city,
            'location_prov' => fake()->stateAbbr,
            'location_postal' => fake()->postcode,
            'location_tele_toll_free' => fake()->tollFreePhoneNumber,
            'fax' => fake()->phoneNumber,
        ];
    }
}
