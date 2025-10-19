<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Cctv;
use Illuminate\Database\Eloquent\Factories\Factory;

class CctvFactory extends Factory
{
    protected $model = Cctv::class;

    public function definition(): array
    {
        return [
            'building_id' => Building::factory(),
            'room_id' => null,
            'name' => $this->faker->word,
            'model' => $this->faker->word,
            'serial_number' => $this->faker->uuid,
            'firmware_version' => '1.0.0',
            'description' => $this->faker->sentence,
            'ip_rtsp' => $this->faker->ipv4,
            'stream_username' => null,
            'stream_password' => null,
            'port' => 554,
            'resolution' => '1920x1080',
            'fps' => 30,
            'recording_schedule' => null,
            'status' => $this->faker->randomElement(['online', 'offline', 'maintenance']),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'hls_path' => null,
            'last_seen_at' => $this->faker->dateTime,
        ];
    }
}
