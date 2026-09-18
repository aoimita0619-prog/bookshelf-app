<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [[
            'title' => '吾輩は猫である',
            'author' => '夏目漱石',
            'isbn' =>'9784101010014',
            'published_date' => '1905-01-01',
            'user_id' => '1',
            'genres' => [1],
        ],
        [
            'title' => '人を動かす',
            'author' => 'D・カーネギー',
            'isbn' =>'9784422100524',
            'published_date' => '1936-10-01',
            'user_id' => '1',
            'genres' => [2,4],
        ],
        [
            'title' => 'リーダブルーコード',
            'author' => 'Dustin Boswell',
            'isbn' =>'9784873115658',
            'published_date' => '2012-06-23',
            'user_id' => '1',
            'genres' => [3],
        ],
        [
            'title' => '七つの習慣',
            'author' => 'スティーブン・R・コヴィー',
            'isbn' =>'9784863940246',
            'published_date' => '2013-08-30',
            'user_id' => '1',
            'genres' => [2,4],
        ],
        [
            'title' => '坊ちゃん',
            'author' => '夏目漱石',
            'isbn' =>'9784101010021',
            'published_date' => '1906-04-01',
            'user_id' => '1',
            'genres' => [1],
        ],
        [
            'title' => 'サピエンス全史',
            'author' => 'ユヴァル・ノア・ハラリ',
            'isbn' =>'9784309226712',
            'published_date' => '2016-09-08',
            'user_id' => '1',
            'genres' => [6,7],
        ],
        [
            'title' => 'Clean Code',
            'author' => ' Robert C. Martin',
            'isbn' =>'9784048930598',
            'published_date' => '2017-12-18',
            'user_id' => '1',
            'genres' => [3],
        ],
        [
            'title' => '嫌われる勇気',
            'author' => '岸見一郎・古賀史健',
            'isbn' =>'9784048930598',
            'published_date' => '2017-12-18',
            'user_id' => '1',
            'genres' => [4],
        ],
        [
            'title' => '火花',
            'author' => '又吉直樹',
            'isbn' =>'9784163902302',
            'published_date' => '2015-03-11',
            'user_id' => '1',
            'genres' => [1],
        ],
        [
            'title' => 'FACTFULNESS',
            'author' => 'ハンス・ロスリング',
            'isbn' =>'9784822289607',
            'published_date' => '2019-01-11',
            'user_id' => '1',
            'genres' => [2,7],
        ],
        [
            'title' => 'コンテナ物語',
            'author' => 'マルク・レビンソン',
            'isbn' =>'9784822251468',
            'published_date' => '2007-01-18',
            'user_id' => '1',
            'genres' => [2,6],
        ],
     ];

     foreach ($books as $data) {
    $genreIds = $data['genres'];

    unset($data['genres']);

    $book = Book::create($data);

    $book->genres()->attach($genreIds);
}
    }
}
