<?php

namespace Modules\Auth\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Auth\App\Models\User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(), // Random name
            'email' => $this->faker->unique()->safeEmail(), // Random unique email
            'phone_number' => $this->faker->unique()->phoneNumber(), // Random unique phone number
            'password' => bcrypt('password'), // Default password
            'is_seller' => $this->faker->boolean(), // Random boolean for seller
            'is_professional' => $this->faker->boolean(), // Random boolean for professional
            'subscription_expires_at' => $this->faker->dateTimeBetween('now', '+1 year'), // Random date within the next year
            'profile_photo_path' => $this->faker->imageUrl(), // Random image URL
            'remember_token' => Str::random(10), // Random remember token
            'created_at' => now(), // Current timestamp
            'updated_at' => now(), // Current timestamp
        ];
    }
}
