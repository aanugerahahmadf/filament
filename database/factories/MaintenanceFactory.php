<?php

namespace Database\Factories;

use App\Models\Cctv;
use App\Models\Maintenance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceFactory extends Factory
{
    protected $model = Maintenance::class;

    public function definition(): array
    {
        return [
            'cctv_id' => Cctv::factory(),
            'technician_id' => User::factory(),
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+1 week'),
            'started_at' => null,
            'completed_at' => null,
            'cancelled_at' => null,
            'status' => $this->faker->randomElement(['scheduled', 'in_progress', 'completed', 'cancelled']),
            'type' => $this->faker->randomElement(['preventive', 'corrective', 'emergency']),
            'description' => $this->faker->sentence,
            'notes' => $this->faker->sentence,
            'cost' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
