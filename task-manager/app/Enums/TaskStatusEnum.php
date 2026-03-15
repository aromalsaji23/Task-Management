<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case Pending    = 'pending';
    case InProgress = 'in_progress';
    case Completed  = 'completed';

    public function label(): string
    {
        return match($this) {
            self::Pending    => 'Pending',
            self::InProgress => 'In Progress',
            self::Completed  => 'Completed',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Pending    => 'bg-gray-100 text-gray-700',
            self::InProgress => 'bg-blue-100 text-blue-800',
            self::Completed  => 'bg-green-100 text-green-800',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
