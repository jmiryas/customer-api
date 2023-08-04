<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $type = fake()->randomElement(["individual", "business"]);

        $name = $type == "individual" ? fake()->name() : fake()->company();

        return [
            "name" => $name,
            "type" => $type,
            "email" => fake()->email(),
            "address" => fake()->streetAddress(),
            "city" => fake()->city(),
            "state" => $this->faker->state(),
            "postal_code" => fake()->postcode()
        ];
    }
}
