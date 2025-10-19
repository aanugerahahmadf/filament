<?php

namespace Database\Factories;

use App\Models\Cctv;
use App\Models\Recording;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecordingFactory extends Factory
{
    protected $model = Recording::class;

    public function definition(): array
    {
        return [
            'cctv_id' => Cctv::factory(),
            'filename' => 'recording_'.$this->faker->uuid.'.mp4',
            'filepath' => 'recordings/recording_'.$this->faker->uuid.'.mp4',
            'size' => $this->faker->numberBetween(1000000, 100000000),
            'duration' => $this->faker->numberBetween(60, 7200),
            'started_at' => $this->faker->dateTime,
            'ended_at' => $this->faker->dateTime,
            'format' => 'mp4',
            'resolution' => '1920x1080',
            'status' => $this->faker->randomElement(['active', 'archived', 'deleted']),
            'notes' => $this->faker->sentence,
        ];
    }
}
