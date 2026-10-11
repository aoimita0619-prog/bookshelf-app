<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Genre;

class GenreTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_genres()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/genres');

        $response->assertStatus(200);
        $response->assertViewIs('genres.index');
        $response->assertViewHas('genres');
    }

    public function test_can_create_genre()
    {
        $user = User::factory()->create();

        $data = [
            'name' => '小説',
        ];

        $response = $this->actingAs($user)
            ->post('/genres/create', $data);

        $response->assertRedirect();

        $this->assertDatabaseHas('genres', [
            'name' => '小説',
        ]);
    }

    public function test_can_update_genre()
    {
        $user = User::factory()->create();

        $genre = Genre::factory()->create();

        $data = [
            'name' => '小説',
        ];

        $response = $this->actingAs($user)
            ->put("/genres/{$genre->id}/edit", $data);

        $response->assertRedirect();

        $this->assertDatabaseHas('genres', [
            'id' => $genre->id,
            'name' => '小説',
        ]);
    }

    public function test_can_delete_genre()
    {
        $user = User::factory()->create();

        $genre = Genre::factory()->create();

        $response = $this->actingAs($user)
            ->delete("/genres/{$genre->id}/delete");

        $response->assertRedirect();

        $this->assertDatabaseMissing('genres', [
            'id' => $genre->id,
        ]);
    }

    public function test_create_genre_requires_name()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/genres/create', []);

        $response->assertSessionHasErrors(['name']);
    }
}