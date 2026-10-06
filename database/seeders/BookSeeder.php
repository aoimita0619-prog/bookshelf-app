<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        $books = [[
            'title' => '吾輩は猫である',
            'author' => '夏目漱石',
            'isbn' =>'9784101010014',
            'published_date' => '1905-01-01',
            'genres' => [1],
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=1',
        ],
        [
            'title' => '人を動かす',
            'author' => 'D・カーネギー',
            'isbn' =>'9784422100524',
            'published_date' => '1936-10-01',
            'genres' => [2,4],
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=2',
        ],
        [
            'title' => 'リーダブルーコード',
            'author' => 'Dustin Boswell',
            'isbn' =>'9784873115658',
            'published_date' => '2012-06-23',
            'genres' => [3],
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=3',
        ],
        [
            'title' => '七つの習慣',
            'author' => 'スティーブン・R・コヴィー',
            'isbn' =>'9784863940246',
            'published_date' => '2013-08-30',
            'genres' => [2,4],
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=4',
        ],
        [
            'title' => '坊ちゃん',
            'author' => '夏目漱石',
            'isbn' =>'9784101010021',
            'published_date' => '1906-04-01',
            'genres' => [1],
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=5',
        ],
        [
            'title' => 'サピエンス全史',
            'author' => 'ユヴァル・ノア・ハラリ',
            'isbn' =>'9784309226712',
            'published_date' => '2016-09-08',
            'genres' => [6,7],
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=6',
        ],
        [
            'title' => 'Clean Code',
            'author' => ' Robert C. Martin',
            'isbn' =>'9784048930598',
            'published_date' => '2017-12-18',
            'genres' => [3],
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=7',
        ],
        [
            'title' => '嫌われる勇気',
            'author' => '岸見一郎・古賀史健',
            'isbn' =>'9784048930598',
            'published_date' => '2017-12-18',
            'genres' => [4],
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=8',
        ],
        [
            'title' => '火花',
            'author' => '又吉直樹',
            'isbn' =>'9784163902302',
            'published_date' => '2015-03-11',
            'genres' => [1],
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=9',
        ],
        [
            'title' => 'FACTFULNESS',
            'author' => 'ハンス・ロスリング',
            'isbn' =>'9784822289607',
            'published_date' => '2019-01-11',
            'genres' => [2,7],
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=10',
        ],
        [
            'title' => 'コンテナ物語',
            'author' => 'マルク・レビンソン',
            'isbn' =>'9784822251468',
            'published_date' => '2007-01-18',
            'genres' => [2,6],
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=11',
            
        ],
     ];

     foreach ($books as $data) {
    $genreIds = $data['genres'];
    $data['user_id'] = $users->random()->id;

    unset($data['genres']);

    $book = Book::create($data);

    $book->genres()->attach($genreIds);
}
    }
}
