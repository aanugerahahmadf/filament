<?php

namespace Database\Factories;

use App\Models\Building;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuildingFactory extends Factory
{
    protected $model = Building::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'description' => $this->faker->sentence,
            'address' => $this->faker->address,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'contact_person' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
        ];
    }
}
