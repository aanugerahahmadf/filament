<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'building_id' => Building::factory(),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'floor' => $this->faker->numberBetween(1, 20),
            'capacity' => $this->faker->numberBetween(1, 100),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
        ];
    }
}
