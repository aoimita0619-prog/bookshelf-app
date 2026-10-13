<?php

namespace Tests\Feature;

use App\Enums\ReadingPlanStatus;
use App\Models\Book;
use App\Models\ReadingPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpireReadingPlansTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_reading_plans_are_updated(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        // 期限切れの進行中計画
        $expiredPlan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => today()->subDay(),
            'status' => ReadingPlanStatus::In_Progress->value,
        ]);

        // 今日が目標日の計画
        $todayPlan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => today(),
            'status' => ReadingPlanStatus::In_Progress->value,
        ]);

        // 期限切れでも読了済みなら変更しない
        $completedPlan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => today()->subDay(),
            'status' => ReadingPlanStatus::Completed->value,
        ]);

        $this->artisan('reading-plans:expire')
            ->assertExitCode(0);

        $this->assertDatabaseHas('reading_plans', [
            'id' => $expiredPlan->id,
            'status' => ReadingPlanStatus::Expired->value,
        ]);

        $this->assertDatabaseHas('reading_plans', [
            'id' => $todayPlan->id,
            'status' => ReadingPlanStatus::In_Progress->value,
        ]);

        $this->assertDatabaseHas('reading_plans', [
            'id' => $completedPlan->id,
            'status' => ReadingPlanStatus::Completed->value,
        ]);
    }
}
