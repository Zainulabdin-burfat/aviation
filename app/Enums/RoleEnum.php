<?php

namespace App\Enums;

class RoleEnum
{
    const SUPERADMIN = 1;
    const ADMIN = 2;
    const BUYER = 3;
    const SELLER = 4;

    public static function all()
    {
        return [
            self::SUPERADMIN,
            self::ADMIN,
            self::BUYER,
            self::SELLER,
        ];
    }

    public static function toString($role)
    {
        return match ($role) {
            self::SUPERADMIN => 'Super Admin',
            self::ADMIN => 'Admin',
            self::BUYER => 'Buyer',
            self::SELLER => 'Seller',
            default => null,
        };
    }
}
