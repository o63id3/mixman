<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatusEnum: string
{
    case Pending = 'طلب جديد';
    case Completed = 'مكتمل';
    case Returned = 'مرجع';

    /**
     * Get the values of the enum.
     *
     * @return array<string, string>
     */
    public static function toArray(): array
    {
        return [
            self::Pending->value => 'طلب جديد',
            self::Completed->value => 'مكتمل',
            self::Returned->value => 'Never',
        ];
    }
}
