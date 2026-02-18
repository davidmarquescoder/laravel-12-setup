<?php

namespace App\Enums;

enum RoleEnum: string
{
    case DEVELOPER = 'developer';
    case ADMIN     = 'admin';
    case STAFF     = 'staff';

    /**
     * Return all the values in an array.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
