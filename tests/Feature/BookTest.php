<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use App\Models\Genre;

class BookTest extends TestCase
{
    public function test_can_get_books()
{
    $response = $this->get('/books');

    $response->assertStatus(200);
    $response->assertViewIs('books.index');
    $response->assertViewHas('books');
}

use RefreshDatabase;
public function test_can_create_book()
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();

$data = [
    'title' => '吾輩は猫である',
    'author' => '夏目漱石',
    'isbn' => '9784003109038',
    'published_date' => '2024-01-01',
    'description' => 'テスト用の書籍です。',
    'genres' => [$genre->id],
];

        $data = [
            'title' => '吾輩は猫である',
            'author' => '夏目漱石',
            'isbn' =>'9784101010014',
            'published_date' => '1905-01-01',
            'genres' =>[$genre->id],
            'description' => '名前のない一匹の雄猫の視点から、主人である中学校の英語教師・珍野苦沙弥（ちんの くしゃみ）や、その友人たちの人間模様を皮肉とユーモア交じりに描いた作品',
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=1',
        ];


        $response = $this->actingAs($user)
            ->post('/books/create', $data);
        $response->dump();

        // 4. レスポンスとデータベースを検証
        $response->assertRedirect();
        $this->assertDatabaseHas('books', ['title' => '吾輩は猫である']);
    }

    public function test_can_update_book()
{

    $user = User::factory()->create();

$genre = Genre::create([
    'name' => '小説',
]);

$book = Book::factory()->create([
    'user_id' => $user->id,
]);

$data = [
    'title' => '人を動かす',
    'author' => 'D・カーネギー',
    'isbn' => '9784422100524',
    'published_date' => '1936-01-01',
    'genres' => [$genre->id],
];

$response = $this->actingAs($user)
    ->put("/books/{$book->id}/edit", $data);

$response->assertRedirect();

$this->assertDatabaseHas('books', [
    'id' => $book->id,
    'title' => '人を動かす',
]);
}

public function test_can_delete_book()
{

    $user = User::factory()->create();
    $book = Book::factory()->create(['user_id' => $user->id]);


    $response = $this->actingAs($user)
        ->delete("/books/{$book->id}/delete");

    $response->assertRedirect();
    $this->assertDatabaseMissing('books', ['id' => $book->id]);

}
public function test_create_book_requires_title()
{
    $user = User::factory()->create();

    // titleを意図的に省略
    $data = [
        'author' => 'D・カーネギー',
        'isbn' =>'9784422100524',
        'published_date' => '1936-10-01',
        'genres' => [2,4],
        'description' => '良好な人間関係を築き、相手に自発的に動いてもらうための「人間関係の30原則」を解説した実用書',
        'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=2',
    ];

    $response = $this->actingAs($user)
        ->post('/books/create', $data);
    $response->dump();
    $response->assertSessionHasErrors(['title']);
}

public function test_create_book_requires_author()
{
    $user = User::factory()->create();

    $data = [
        'title' => '人を動かす',
        'isbn' =>'9784422100524',
        'published_date' => '1936-10-01',
        'genres' => [2,4],
        'description' => '良好な人間関係を築き、相手に自発的に動いてもらうための「人間関係の30原則」を解説した実用書',
        'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=2',
    ];

    $response = $this->actingAs($user)
        ->post('/books/create', $data);
    $response->dump();
    $response->assertSessionHasErrors(['author']);
}

public function test_create_book_requires_isbn()
{
    $user = User::factory()->create();

    $data = [
        'title' => '人を動かす',
        'author' => 'D・カーネギー',
        'published_date' => '1936-10-01',
        'genres' => [2,4],
        'description' => '良好な人間関係を築き、相手に自発的に動いてもらうための「人間関係の30原則」を解説した実用書',
        'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=2',
    ];

    $response = $this->actingAs($user)
        ->post('/books/create', $data);
    $response->dump();
    $response->assertSessionHasErrors(['isbn']);
}
public function test_create_book_requires_published_date()
{
    $user = User::factory()->create();

    $data = [
        'title' => '人を動かす',
        'author' => 'D・カーネギー',
        'isbn' =>'9784422100524',
        'genres' => [2,4],
        'description' => '良好な人間関係を築き、相手に自発的に動いてもらうための「人間関係の30原則」を解説した実用書',
        'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=2',
    ];

    $response = $this->actingAs($user)
        ->post('/books/create', $data);
    $response->dump();
    $response->assertSessionHasErrors(['published_date']);
}

public function test_create_book_requires_genres()
{
    $user = User::factory()->create();

    $data = [
        'title' => '人を動かす',
        'author' => 'D・カーネギー',
        'isbn' =>'9784422100524',
        'published_date' => '1936-10-01',
        'description' => '良好な人間関係を築き、相手に自発的に動いてもらうための「人間関係の30原則」を解説した実用書',
        'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=2',
    ];

    $response = $this->actingAs($user)
        ->post('/books/create', $data);
    $response->dump();
    $response->assertSessionHasErrors(['genres']);
}

public function test_can_search_books_by_keyword()
{
    $matchedBook = Book::factory()->create([
        'title' => '吾輩は猫である',
        'author' => '夏目漱石',
    ]);

    $unmatchedBook = Book::factory()->create([
        'title' => '人を動かす',
        'author' => 'D・カーネギー',
    ]);

    $response = $this->get('/books?keyword=猫');

    $response->assertOk();
    $response->assertViewIs('books.index');

    $response->assertViewHas('books', function ($books) use (
        $matchedBook,
        $unmatchedBook
    ) {
        return $books->contains('id', $matchedBook->id)
            && ! $books->contains('id', $unmatchedBook->id);
    });
}

public function test_can_filter_books_by_genre()
{
    $novel = Genre::create([
        'name' => '小説',
    ]);

    $business = Genre::create([
        'name' => 'ビジネス',
    ]);

    $novelBook = Book::factory()->create([
        'title' => '吾輩は猫である',
    ]);

    $businessBook = Book::factory()->create([
        'title' => '人を動かす',
    ]);

    $novelBook->genres()->attach($novel->id);
    $businessBook->genres()->attach($business->id);

    $response = $this->get('/books?genre=' . $novel->id);

    $response->assertOk();

    $response->assertViewHas('books', function ($books) use (
        $novelBook,
        $businessBook
    ) {
        return $books->contains('id', $novelBook->id)
            && ! $books->contains('id', $businessBook->id);
    });
}

public function test_can_sort_books_by_latest()
{
    $oldBook = Book::factory()->create([
        'created_at' => now()->subDays(2),
    ]);

    $newBook = Book::factory()->create([
        'created_at' => now(),
    ]);

    $response = $this->get('/books?sort=latest');

    $response->assertOk();

    $response->assertViewHas('books', function ($books) use (
        $newBook,
        $oldBook
    ) {
        return $books->first()->id === $newBook->id
            && $books->last()->id === $oldBook->id;
    });
}

public function test_can_sort_books_by_oldest()
{
    $oldBook = Book::factory()->create([
        'created_at' => now()->subDays(2),
    ]);

    $newBook = Book::factory()->create([
        'created_at' => now(),
    ]);

    $response = $this->get('/books?sort=oldest');

    $response->assertOk();

    $response->assertViewHas('books', function ($books) use (
        $oldBook,
        $newBook
    ) {
        return $books->first()->id === $oldBook->id
            && $books->last()->id === $newBook->id;
    });
}

public function test_can_sort_books_by_title()
{
    $zBook = Book::factory()->create([
        'title' => '吾輩は猫である',
    ]);

    $aBook = Book::factory()->create([
        'title' => '人を動かす',
    ]);

    $response = $this->get('/books?sort=title');

    $response->assertOk();

    $response->assertViewHas('books', function ($books) use (
        $aBook,
        $zBook
    ) {
        return $books->first()->id === $aBook->id
            && $books->last()->id === $zBook->id;
    });
}

public function test_can_sort_books_by_rating()
{
    $user = User::factory()->create();

    $lowRatedBook = Book::factory()->create();
    $highRatedBook = Book::factory()->create();

    Review::factory()->create([
        'book_id' => $lowRatedBook->id,
        'user_id' => $user->id,
        'rating' => 3,
    ]);

    Review::factory()->create([
        'book_id' => $highRatedBook->id,
        'user_id' => $user->id,
        'rating' => 5,
    ]);

    $response = $this->get('/books?sort=rating');

    $response->assertOk();

    $response->assertViewHas('books', function ($books) use (
        $highRatedBook,
        $lowRatedBook
    ) {
        return $books->first()->id === $highRatedBook->id
            && $books->last()->id === $lowRatedBook->id;
    });
}

}
