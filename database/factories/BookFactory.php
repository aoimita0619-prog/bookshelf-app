<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'author' => fake()->name(),
            'isbn' => fake()->unique()->numerify('978##########'),
            'published_date' => fake()->date(),
            'description' => fake()->paragraph(),
            'image_url' => fake()->imageUrl(),
            'user_id' => User::factory(),
        ];
    }
}
