<?php

namespace App\Enums;

enum RolesEnum: string
{
    case Basic = 'basic';
    case SuperAdmin = 'super-admin';

    public function label(): string
    {
        return match ($this) {
            self::Basic => 'Basic',
            self::SuperAdmin => 'Super Admin',
        };
    }
}
