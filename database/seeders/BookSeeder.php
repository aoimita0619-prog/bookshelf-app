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
            'description' => '名前のない一匹の雄猫の視点から、主人である中学校の英語教師・珍野苦沙弥（ちんの くしゃみ）や、その友人たちの人間模様を皮肉とユーモア交じりに描いた作品',
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=1',
        ],
        [
            'title' => '人を動かす',
            'author' => 'D・カーネギー',
            'isbn' =>'9784422100524',
            'published_date' => '1936-10-01',
            'genres' => [2,4],
            'description' => '良好な人間関係を築き、相手に自発的に動いてもらうための「人間関係の30原則」を解説した実用書',
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=2',
        ],
        [
            'title' => 'リーダブルーコード',
            'author' => 'Dustin Boswell',
            'isbn' =>'9784873115658',
            'published_date' => '2012-06-23',
            'genres' => [3],
            'description' => 'プログラミングの「解説書（技術書）」',
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=3',
        ],
        [
            'title' => '七つの習慣',
            'author' => 'スティーブン・R・コヴィー',
            'isbn' =>'9784863940246',
            'published_date' => '2013-08-30',
            'genres' => [2,4],
            'description' => '人生をより良く生きるための普遍的な原則を説いた世界的なベストセラー',
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=4',
        ],
        [
            'title' => '坊ちゃん',
            'author' => '夏目漱石',
            'isbn' =>'9784101010021',
            'published_date' => '1906-04-01',
            'genres' => [1],
            'description' => '親譲りの無鉄砲な青年が、赴任先の四国で悪知恵の働く教頭らと対立し、正義を貫いて東京へ帰る物語',
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=5',
        ],
        [
            'title' => 'サピエンス全史',
            'author' => 'ユヴァル・ノア・ハラリ',
            'isbn' =>'9784309226712',
            'published_date' => '2016-09-08',
            'genres' => [6,7],
            'description' => '無力な一動物にすぎなかったホモ・サピエンスが、いかにして地球の覇者となったのかを「3大革命」を軸に解き明かす壮大な人類史',
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=6',
        ],
        [
            'title' => 'Clean Code',
            'author' => ' Robert C. Martin',
            'isbn' =>'9784048930598',
            'published_date' => '2017-12-18',
            'genres' => [3],
            'description' => '動くだけのコードから「読みやすく、保守しやすく、拡張しやすいコード」を書くための原則と流儀を説いたプログラミングのバイブル',
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=7',
        ],
        [
            'title' => '嫌われる勇気',
            'author' => '岸見一郎・古賀史健',
            'isbn' =>'9784048930598',
            'published_date' => '2017-12-18',
            'genres' => [4],
            'description' => '哲学者と悩める青年の対話を通して、アルフレッド・アドラーの思想である「アドラー心理学」を分かりやすく解き明かすベストセラー書籍',
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=8',
        ],
        [
            'title' => '火花',
            'author' => '又吉直樹',
            'isbn' =>'9784163902302',
            'published_date' => '2015-03-11',
            'genres' => [1],
            'description' => 'れない漫才コンビ「スパークス」のボケ担当・徳永と、型破りな天才肌の先輩芸人・神谷の10年間にわたる交流を描いた第153回芥川賞受賞作',
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=9',
        ],
        [
            'title' => 'FACTFULNESS',
            'author' => 'ハンス・ロスリング',
            'isbn' =>'9784822289607',
            'published_date' => '2019-01-11',
            'genres' => [2,7],
            'description' => '人間が無意識に持ってしまう10の「思い込み（本能）」を解説し、データに基づいて世界を正しく見る習慣（ファクトフルネス）を説いたベストセラー書籍',
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=10',
        ],
        [
            'title' => 'コンテナ物語',
            'author' => 'マルク・レビンソン',
            'isbn' =>'9784822251468',
            'published_date' => '2007-01-18',
            'genres' => [2,6],
            'description' => 'ただの「箱」である輸送用コンテナの誕生が、世界の物流と経済の仕組みを根本から変えた歴史を描いたノンフィクション',
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
