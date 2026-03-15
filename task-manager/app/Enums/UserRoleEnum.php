<?php

namespace App\Enums;

enum UserRoleEnum: string
{
    case Admin = 'admin';
    case User  = 'user';

    public function label(): string
    {
        return match($this) {
            self::Admin => 'Administrator',
            self::User  => 'User',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
