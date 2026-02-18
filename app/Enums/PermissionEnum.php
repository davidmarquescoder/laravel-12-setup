<?php

namespace App\Enums;

enum PermissionEnum: string
{
    // Genéricas
    case VIEW   = 'view';
    case CREATE = 'create';
    case EDIT   = 'edit';
    case DELETE = 'delete';

    /**
     * Return all the values in an array.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
