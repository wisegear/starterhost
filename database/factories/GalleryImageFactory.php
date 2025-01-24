<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class GalleryImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->words(5, true);
        $slug = Str::slug($title, '-');
    
        return [
            'title' => $title,
            'slug' => $slug,
            'image' => 'https://placehold.co/600x400/000000/FFF',
            'summary' => $this->faker->paragraph(5, false),
            'location' => $this->faker->city(),
            'text' => $this->faker->paragraph(5, false),
            'album_id' => $this->faker->numberBetween(1, 20),
            'user_id' => $this->faker->numberBetween(1, 4),
            'created_at' => $this->faker->dateTimeThisYear(),
            'date_taken' => $this->faker->dateTimeThisYear(),
        ];
    }
}
