<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\User;
use App\Models\Favorite;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $books = Book::all();

        foreach ($books as $book) {

            $favoriteCount = rand(3, 5);

            $favoriteUsers = $users->random(
                min($favoriteCount, $users->count())
            );

            foreach ($favoriteUsers as $user) {

                Favorite::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                ]);
            }
        }
    }
}
