<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Genre;

class ApiTest extends TestCase
{
    use RefreshDatabase;

public function test_can_create_book()
    {
        $user = User::factory()->create();
        $genre = Genre::create([
          'name' => '小説',
        ]);

        $data = [
            'title' => '吾輩は猫である',
            'author' => '夏目漱石',
            'isbn' =>'9784101010014',
            'published_date' => '1905-01-01',
            'genres' => [$genre->id],
            'description' => '名前のない一匹の雄猫の視点から、主人である中学校の英語教師・珍野苦沙弥（ちんの くしゃみ）や、その友人たちの人間模様を皮肉とユーモア交じりに描いた作品',
            'image_url' =>'https://placehold.co/200x300/e2e8f0/475569?text=1',
        ];


       $response = $this->actingAs($user)
    ->postJson('/api/v1/books', $data);

$response->assertCreated();

$this->assertDatabaseHas('books', [
    'title' => '吾輩は猫である',
]);
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
    ->putJson("/api/v1/books/{$book->id}", $data);
$response->assertOk();

$this->assertDatabaseHas('books', [
    'id' => $book->id,
    'title' => '人を動かす',
]);
}

public function test_can_delete_book()
{
    $user = User::factory()->create();

    $book = Book::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)
        ->deleteJson("/api/v1/books/{$book->id}");

    $response->assertOk();

    $this->assertDatabaseMissing('books', [
        'id' => $book->id,
    ]);
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
    ->postJson('/api/v1/books', $data);
   $response->assertUnprocessable();
   $response->assertJsonValidationErrors(['title']);
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
    ->postJson('/api/v1/books', $data);

$response->assertUnprocessable();
$response->assertJsonValidationErrors(['author']);
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
    ->postJson('/api/v1/books', $data);

$response->assertUnprocessable();
$response->assertJsonValidationErrors(['isbn']);
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
    ->postJson('/api/v1/books', $data);
$response->assertUnprocessable();
$response->assertJsonValidationErrors(['published_date']);
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
    ->postJson('/api/v1/books', $data);

$response->assertUnprocessable();
$response->assertJsonValidationErrors(['genres']);
}


public function test_guest_cannot_create_book(): void
{
    $response = $this->postJson('/api/v1/books', [
        'title' => 'テスト書籍',
        'author' => 'テスト著者',
        'isbn' => '9784003109038',
        'published_date' => '2024-01-01',
        'genres' => [],
    ]);

    $response->assertUnauthorized();
}

public function test_guest_cannot_update_book(): void
{
    $book = \App\Models\Book::factory()->create();

    $response = $this->putJson(
        "/api/v1/books/{$book->id}",
        [
            'title' => '更新後の書籍',
            'author' => '更新後の著者',
            'isbn' => '9784003109038',
            'published_date' => '2024-01-01',
            'genres' => [],
        ]
    );

    $response->assertUnauthorized();
}

public function test_guest_cannot_delete_book(): void
{
    $book = \App\Models\Book::factory()->create();

    $response = $this->deleteJson(
        "/api/v1/books/{$book->id}"
    );

    $response->assertUnauthorized();

    // 未認証のため、書籍が削除されていないことも確認
    $this->assertDatabaseHas('books', [
        'id' => $book->id,
    ]);
}

}
