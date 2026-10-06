<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\User;
use App\Models\Review;


class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $books = Book::all();

        foreach ($books as $book) {


            $reviewCount = rand(2, 4);

            $reviewUsers = $users->random(
                min($reviewCount, $users->count())
            );

            foreach ($reviewUsers as $user) {
                $rating = rand(1, 5);

                Review::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'rating' => $rating,
                    'comment' => $this->randomComment($rating),
                ]);
            }
        }
    }

    private function randomComment(int $rating): string
{
    $comments = [
        1 => [
            '残念ながら合いませんでした。',
            '期待と違いました。',
        ],
        2 => [
            '少し期待外れでした。',
            '内容が薄い印象。',
            'もう少し深掘りしてほしかった。',
        ],
        3 => [
            '普通でした。',
            '可もなく不可もなく。',
            '期待したほどではなかった。',
        ],
        4 => [
            'とても参考になりました。',
            '読みやすくておすすめです。',
            '期待通りの内容でした。',
        ],
        5 => [
            '素晴らしい本でした！',
            '人生が変わりました。',
            '何度も読み返しています。',
        ],
    ];

    return $comments[$rating][array_rand($comments[$rating])];
}
}
    

