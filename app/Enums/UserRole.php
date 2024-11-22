<?php

namespace App\Enums;

enum UserRole: string
{
    case USER = '0';
    case ADMIN = '1';

    public function label(): string
    {
        return match($this) {
            self::USER => 'user',
            self::ADMIN => 'admin',
        };
    }

    public static function all(): array
    {
        return [
            self::USER,
            self::ADMIN,
        ];
    }
}
