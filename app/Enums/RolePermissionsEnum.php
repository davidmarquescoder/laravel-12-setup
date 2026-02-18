<?php

namespace App\Enums;

enum RolePermissionsEnum: string
{
    case DEVELOPER = 'developer';
    case ADMIN     = 'admin';
    case STAFF     = 'staff';
    case PROVIDER  = 'provider';

    public function permissions(): array
    {
        return match ($this) {
            self::DEVELOPER => PermissionEnum::values(),
            self::ADMIN     => [
                PermissionEnum::VIEW->value,
                PermissionEnum::CREATE->value,
                PermissionEnum::EDIT->value,
                PermissionEnum::DELETE->value,
            ],
            self::STAFF => [
                PermissionEnum::VIEW->value,
            ],
            self::PROVIDER => [
                PermissionEnum::VIEW->value,
                PermissionEnum::CREATE->value,
                PermissionEnum::EDIT->value,
                PermissionEnum::DELETE->value,
            ],
        };
    }
}
