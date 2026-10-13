<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\Book;
use App\Models\User;


class IsbnSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_isbn_search_returns_book_information(): void
{
    $user = User::factory()->create();
    $this->actingAs($user);

    Http::fake([
        'www.googleapis.com/books/v1/volumes*' => Http::response([
            'totalItems' => 1,
            'items' => [
                [
                    'volumeInfo' => [
                        'title' => 'テスト書籍',
                        'authors' => ['テスト著者'],
                        'publishedDate' => '2024-01-01',
                        'description' => 'テスト用の書籍です。',
                        'imageLinks' => [
                            'thumbnail' => 'https://example.com/book.jpg',
                        ],
                        'industryIdentifiers' => [
                            [
                                'type' => 'ISBN_13',
                                'identifier' => '9784003109038',
                            ],
                        ],
                    ],
                ],
            ],
        ], 200),
    ]);

    $response = $this->getJson('/books/isbn/9784003109038');

    $response->assertOk();
    $response->assertJsonPath('title', 'テスト書籍');
    $response->assertJsonPath('author', 'テスト著者');
    $response->assertJsonPath('isbn', '9784003109038');
}
    public function test_isbn_search_returns_error_when_book_not_found(): void
{
    $user = User::factory()->create();
    $this->actingAs($user);

    Http::fake([
        'www.googleapis.com/books/v1/volumes*' => Http::response([
            'totalItems' => 0,
            'items' => [],
        ], 200),
    ]);

    $response = $this->getJson('/books/isbn/9784003109038');

    $response->assertJson([
        'error' => '該当する書籍が見つかりませんでした。',
    ]);
}

    public function test_isbn_search_rejects_invalid_isbn(): void
{
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $response = $this->getJson('/books/isbn/12345');

    $response->assertJsonStructure([
        'error',
    ]);
}

}
