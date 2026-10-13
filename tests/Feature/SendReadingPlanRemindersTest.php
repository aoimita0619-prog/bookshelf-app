<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\ReadingPlan;
use App\Notifications\ReadingPlanReminder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Carbon;

class SendReadingPlanRemindersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2026-10-12 09:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    private function createPlan(string $targetDate, string $status = 'in_progress'): array
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $plan = ReadingPlan::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => $targetDate,
            'status' => $status,
        ]);

        return [$user, $plan];
    }

    public function test_three_days_before_target_date_sends_reminder(): void
    {
        Notification::fake();

        [$user] = $this->createPlan('2026-10-15');

        $this->artisan('reading-plans:send-reminders')
            ->assertExitCode(0);

        Notification::assertSentTo(
            $user,
            ReadingPlanReminder::class
        );
    }

    public function test_target_date_sends_reminder(): void
    {
        Notification::fake();

        [$user] = $this->createPlan('2026-10-12');

        $this->artisan('reading-plans:send-reminders')
            ->assertExitCode(0);

        Notification::assertSentTo(
            $user,
            ReadingPlanReminder::class
        );
    }

    public function test_three_days_after_target_date_sends_reminder(): void
    {
        Notification::fake();

        [$user] = $this->createPlan('2026-10-09');

        $this->artisan('reading-plans:send-reminders')
            ->assertExitCode(0);

        Notification::assertSentTo(
            $user,
            ReadingPlanReminder::class
        );
    }

    public function test_other_dates_do_not_send_reminder(): void
    {
        Notification::fake();

        [$user] = $this->createPlan('2026-10-13');

        $this->artisan('reading-plans:send-reminders')
            ->assertExitCode(0);

        Notification::assertNotSentTo(
            $user,
            ReadingPlanReminder::class
        );
    }

    public function test_completed_plan_does_not_send_reminder(): void
    {
        Notification::fake();

        [$user] = $this->createPlan(
            '2026-10-12',
            'completed'
        );

        $this->artisan('reading-plans:send-reminders')
            ->assertExitCode(0);

        Notification::assertNotSentTo(
            $user,
            ReadingPlanReminder::class
        );
    }
}
