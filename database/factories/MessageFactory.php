<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        return [
            'from_user_id' => User::factory(),
            'to_user_id' => User::factory(),
            'body' => $this->faker->sentence,
            'subject' => $this->faker->sentence(3),
            'type' => 'message',
            'priority' => 'medium',
        ];
    }
}
