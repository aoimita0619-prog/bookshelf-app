<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RankingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_ranking()
    {
        $response = $this->get('/ranking');

        $response->assertStatus(200);
        $response->assertViewIs('ranking.index');
        $response->assertViewHas('rankedBooks');
    }


    public function test_books_without_reviews_are_excluded()
    {
        $user = User::factory()->create();

        $bookWithReview = Book::factory()->create();
        $bookWithoutReview = Book::factory()->create();

        Review::factory()->create([
            'user_id' => $user->id,
            'book_id' => $bookWithReview->id,
            'rating' => 5,
        ]);

        $response = $this->get('/ranking');

        $response->assertStatus(200);

        $rankedBooks = $response->viewData('rankedBooks');

        $this->assertTrue(
            $rankedBooks->contains('id', $bookWithReview->id)
        );

        $this->assertFalse(
            $rankedBooks->contains('id', $bookWithoutReview->id)
        );
    }

    public function test_books_are_ordered_by_average_rating()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $lowRatedBook = Book::factory()->create();
        $highRatedBook = Book::factory()->create();

        Review::factory()->create([
            'user_id' => $user1->id,
            'book_id' => $lowRatedBook->id,
            'rating' => 2,
        ]);

        Review::factory()->create([
            'user_id' => $user2->id,
            'book_id' => $highRatedBook->id,
            'rating' => 5,
        ]);

        $response = $this->get('/ranking');

        $rankedBooks = $response->viewData('rankedBooks');

        $this->assertEquals(
            $highRatedBook->id,
            $rankedBooks->first()->id
        );
    }

    public function test_ranking_contains_at_most_ten_books()
    {
        $users = User::factory()->count(11)->create();

        $books = Book::factory()->count(11)->create();

        foreach ($books as $index => $book) {
            Review::factory()->create([
                'user_id' => $users[$index]->id,
                'book_id' => $book->id,
                'rating' => 5,
            ]);
        }

        $response = $this->get('/ranking');

        $response->assertStatus(200);

        $rankedBooks = $response->viewData('rankedBooks');

        $this->assertCount(10, $rankedBooks);
    }
}