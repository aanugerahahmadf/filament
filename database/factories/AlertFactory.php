<?php

namespace Database\Factories;

use App\Models\Alert;
use App\Models\Cctv;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlertFactory extends Factory
{
    protected $model = Alert::class;

    public function definition(): array
    {
        return [
            'alertable_type' => Cctv::class,
            'alertable_id' => Cctv::factory(),
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
            'severity' => $this->faker->randomElement(['critical', 'high', 'medium', 'low']),
            'status' => $this->faker->randomElement(['active', 'acknowledged', 'resolved', 'suppressed']),
            'category' => $this->faker->randomElement(['system', 'security', 'network', 'hardware', 'software']),
            'source' => $this->faker->randomElement(['cctv_service', 'system', 'user']),
            'triggered_at' => $this->faker->dateTime,
            'acknowledged_at' => null,
            'resolved_at' => null,
            'suppressed_at' => null,
            'data' => [],
        ];
    }
}
