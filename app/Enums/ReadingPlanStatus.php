<?php

namespace App\Enums;

enum ReadingPlanStatus: string
{
    case In_Progress = 'in_progress';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::In_Progress => '進行中',
            self::Completed => '読了',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::In_Progress => 'bg-gray-100 text-gray-800',
            self::Completed => 'bg-green-100 text-green-800',
        };
    }
}