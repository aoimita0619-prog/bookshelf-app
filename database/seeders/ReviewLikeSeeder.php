<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Review;
use App\Models\ReviewLike;


class ReviewLikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $reviews = Review::all();

        Review::all()->each(function ($review) use ($users) {

            $reviewLikeCount = rand(0, min(3, $users->count()));

            $reviewLikeUsers = $users->random($reviewLikeCount);

            foreach ($reviewLikeUsers as $user) {

                ReviewLike::create([
                    'user_id' => $user->id,
                    'review_id' => $review->id,
                ]);
            }
        });
       
    }
}
