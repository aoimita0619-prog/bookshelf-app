<?php

namespace Database\Factories;

use App\Models\ReadingPlan;
use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReadingPlanFactory extends Factory
{
    protected $model = ReadingPlan::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'target_date' => fake()->date(),
            'status' => 'in_progress',
        ];
    }
}