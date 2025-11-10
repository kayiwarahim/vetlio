<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'content' => fake()->paragraph(),
            'for_clients' => fake()->boolean(),
            'for_users' => fake()->boolean(),
            'user_id' => 1,
            'starts_at' => fake()->dateTime(),
            'ends_at' => fake()->dateTime(),
            'active' => fake()->boolean(),
            'organisation_id' => 1,
        ];
    }
}
