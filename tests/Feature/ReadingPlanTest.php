<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\ReadingPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadingPlanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログインユーザーの読書計画一覧が表示される
     */
    public function test_can_view_reading_plans(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $plan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => '2026-11-01',
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($user)
            ->get(route('reading-plans.index'));

        $response->assertOk();
        $response->assertViewIs('reading-plans.index');
        $response->assertViewHas('readingPlans');

        $response->assertSee($book->title);
    }

    /**
     * 読書計画を新規登録できる
     */
    public function test_can_create_reading_plan(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $data = [
            'book_id' => $book->id,
            'target_date' => '2026-11-01',
        ];

        $response = $this->actingAs($user)
            ->post(route('reading-plans.store'), $data);

        $response->assertRedirect();

        $this->assertDatabaseHas('reading_plans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => '2026-11-01',
        ]);
    }

    /**
     * 読書計画の編集画面を表示できる
     */
    public function test_can_view_edit_reading_plan(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $plan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => '2026-11-01',
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($user)
            ->get(route('reading-plans.edit', $plan));

        $response->assertOk();
        $response->assertViewIs('reading-plans.edit');
    }

    /**
     * 読書計画を更新できる
     */
    public function test_can_update_reading_plan(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $plan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => '2026-11-01',
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($user)
            ->put(route('reading-plans.update', $plan), [
                'book_id' => $book->id,
                'target_date' => '2026-12-01',
                'status' => 'in_progress',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('reading_plans', [
            'id' => $plan->id,
            'user_id' => $user->id,
            'target_date' => '2026-12-01',
        ]);
    }

    /**
     * 読書計画を削除できる
     */
    public function test_can_delete_reading_plan(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $plan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => '2026-11-01',
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($user)
            ->delete(route('reading-plans.destroy', $plan));

        $response->assertRedirect();

        $this->assertDatabaseMissing('reading_plans', [
            'id' => $plan->id,
        ]);
    }
}
