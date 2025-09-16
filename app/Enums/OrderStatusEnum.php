<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OrderStatusEnum extends Enum
{

    const STATUS_WAIT = 0;
    const STATUS_PAID = 1;
    const STATUS_CANCELLED = 2;

    public static function labels(): array
    {
        return [
            self::STATUS_WAIT => __("Ожидает"),
            self::STATUS_PAID => __("Оплачен"),
            self::STATUS_CANCELLED => __("Отменен"),
        ];
    }
}
