<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Genre;
use App\Models\Review;

class ReviewTest extends TestCase
{
use RefreshDatabase;
public function test_can_create_review()
    {
        $user = User::factory()->create();
        $genre = Genre::create([
           'name' => '小説',
        ]);
        $book = Book::factory()->create();

        $data = [
            'rating' => 5,
            'comment' => '面白かったです',
        ];


        $response = $this->actingAs($user)
            ->post("/books/{$book->id}/review", $data);
        $response->dump();

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
        'user_id' => $user->id,
        'book_id' => $book->id,
        'rating' => $data['rating'],
        'comment' => $data['comment'],
        ]);
    }

    public function test_can_update_review()
{

    $user = User::factory()->create();

   $genre = Genre::create([
       'name' => '小説',
   ]);

   $book = Book::factory()->create();

$review = Review::factory()->create([
    'user_id' => $user->id,
]);

$data = [
    'rating' => 5,
    'comment' => '面白かったです',
];

$response = $this->actingAs($user)
    ->put("/reviews/{$review->id}", $data);

$response->assertRedirect();

$this->assertDatabaseHas('reviews', [
    'id' => $review->id,
    'rating' => 5,
    'comment' => '面白かったです',
]);
}

public function test_can_delete_review()
{

    $user = User::factory()->create();
    $book = Book::factory()->create();
    $review = Review::factory()->create(['user_id' => $user->id]);


    $response = $this->actingAs($user)
        ->delete("/reviews/{$review->id}/delete");

    $response->assertRedirect();
    $this->assertDatabaseMissing('reviews', ['id' => $review->id]);

}
public function test_create_book_requires_rating()
{
    $user = User::factory()->create();
    $book = Book::factory()->create();

    $data = [
        'comment' => '面白かったです',
    ];

    $response = $this->actingAs($user)
        ->post("/books/{$book->id}/review", $data);
    $response->dump();
    $response->assertSessionHasErrors(['rating']);
}

public function test_create_book_requires_comment()
{
    $user = User::factory()->create();
    $book = Book::factory()->create();

    $data = [
        'rating' => 5,
    ];

    $response = $this->actingAs($user)
        ->post("/books/{$book->id}/review", $data);
    $response->dump();
    $response->assertSessionHasErrors(['comment']);
}

}
