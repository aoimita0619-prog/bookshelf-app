<?php

namespace App\Console\Commands;

use App\Enums\ReadingPlanStatus;
use App\Models\ReadingPlan;
use App\Notifications\ReadingPlanReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendReadingPlanReminders extends Command
{
    protected $signature = 'reading-plans:send-reminders';

    protected $description = '進行中の読書計画にリマインダー通知を送信する';

    public function handle(): int
    {
        $today = Carbon::today();

        $this->sendReminders(
            $today->copy()->addDays(3),
            'before_3_days'
        );

        $this->sendReminders(
            $today,
            'today'
        );

        $this->sendReminders(
            $today->copy()->subDays(3),
            'after_3_days'
        );

        return self::SUCCESS;
    }

    private function sendReminders(
        Carbon $targetDate,
        string $timing
    ): void {
        $readingPlans = ReadingPlan::with(['user', 'book'])
            ->where('status', ReadingPlanStatus::In_Progress->value,)
            ->whereDate('target_date', $targetDate)
            ->get();

        $this->info(
            "{$timing}: {$readingPlans->count()}件"
        );

        foreach ($readingPlans as $readingPlan) {

            $readingPlan->user->notify(
                new ReadingPlanReminder(
                    $readingPlan,
                    $timing
                )
            );

            $this->info(
                "通知送信: {$readingPlan->book->title}"
            );
        }
    }
}