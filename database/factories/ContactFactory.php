<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'whatsapp' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'instagram' => $this->faker->userName,
            'facebook' => $this->faker->userName,
            'linkedin' => $this->faker->userName,
            'position' => $this->faker->jobTitle,
            'department' => $this->faker->word,
            'phone' => $this->faker->phoneNumber,
            'notes' => $this->faker->sentence,
        ];
    }
}
