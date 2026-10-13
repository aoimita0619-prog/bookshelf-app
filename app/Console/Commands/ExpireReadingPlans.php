<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ReadingPlan;
use App\Enums\ReadingPlanStatus;

class ExpireReadingPlans extends Command
{
    protected $signature = 'reading-plans:expire';

    protected $description = '期限切れの読書計画を失効させる';

    public function handle(): int
    {
        $count = ReadingPlan::where(
            'status',
            ReadingPlanStatus::In_Progress->value
        )
            ->whereDate('target_date', '<', today())
            ->update([
                'status' => ReadingPlanStatus::Expired->value,
            ]);

        $this->info("{$count}件の読書計画を失効させました。");

        return self::SUCCESS;
    }
}
