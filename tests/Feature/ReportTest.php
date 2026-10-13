<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Genre;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_reading_report(): void
    {
        // ユーザーを作成
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        // ジャンルを作成
        $novel = Genre::factory()->create([
            'name' => '小説',
        ]);

        $business = Genre::factory()->create([
            'name' => 'ビジネス',
        ]);

        // 自分がレビューする書籍を作成
        $highRatedBook = Book::factory()->create([
            'user_id' => $user->id,
            'title' => '高評価の書籍',
        ]);

        $lowRatedBook = Book::factory()->create([
            'user_id' => $user->id,
            'title' => '低評価の書籍',
        ]);

        // 書籍にジャンルを紐付ける
        $highRatedBook->genres()->attach($novel->id);

        $lowRatedBook->genres()->attach([
            $novel->id,
            $business->id,
        ]);

        // 自分のレビューを作成
        Review::factory()->create([
            'user_id' => $user->id,
            'book_id' => $highRatedBook->id,
            'rating' => 5,
        ]);

        Review::factory()->create([
            'user_id' => $user->id,
            'book_id' => $lowRatedBook->id,
            'rating' => 3,
        ]);

        // 他のユーザーのレビューを作成
        $otherBook = Book::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        Review::factory()->create([
            'user_id' => $otherUser->id,
            'book_id' => $otherBook->id,
            'rating' => 1,
        ]);

        // ログインして読書レポートにアクセス
        $response = $this->actingAs($user)
            ->get(route('reports.index'));

        // 画面が正常に表示されること
        $response->assertOk();
        $response->assertViewIs('reports.index');
        $response->assertViewHas('stats');

        $stats = $response->viewData('stats');

        // 1. サマリーの集計を確認
        $this->assertSame(
            2,
            $stats['summary']['total_reviews']
        );

        $this->assertSame(
            2,
            $stats['summary']['books_read']
        );

        $this->assertEquals(
            4.0,
            (float) $stats['summary']['average_rating']
        );

        // 2. 評価別の件数を確認
        // 配列のキー0～4は、評価1～5に対応
        $this->assertSame(0, $stats['rating_distribution'][0]);
        $this->assertSame(0, $stats['rating_distribution'][1]);
        $this->assertSame(1, $stats['rating_distribution'][2]);
        $this->assertSame(0, $stats['rating_distribution'][3]);
        $this->assertSame(1, $stats['rating_distribution'][4]);

        // 3. 評価4以上の書籍を確認
        $this->assertCount(1, $stats['top_rated_books']);

        $this->assertSame(
            '高評価の書籍',
            $stats['top_rated_books'][0]['title']
        );

        $this->assertSame(
            5,
            $stats['top_rated_books'][0]['rating']
        );

        // 4. ジャンル別の平均評価を確認
        $this->assertCount(2, $stats['genre_ratings']);

        // 小説：評価5と評価3の平均は4.0
        $this->assertSame(
            '小説',
            $stats['genre_ratings'][0]['name']
        );

        $this->assertEquals(
            4.0,
            $stats['genre_ratings'][0]['average_rating']
        );

        // ビジネス：評価3の平均は3.0
        $this->assertSame(
            'ビジネス',
            $stats['genre_ratings'][1]['name']
        );

        $this->assertEquals(
            3.0,
            $stats['genre_ratings'][1]['average_rating']
        );
    }
}
