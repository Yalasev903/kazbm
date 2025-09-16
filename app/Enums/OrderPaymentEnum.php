<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OrderPaymentEnum extends Enum
{

    const PAYMENT_XALYK = 0;
    const PAYMENT_INVOICE = 1;
    const PAYMENT_KASPI = 2;

    public static function labels(): array
    {
        return [
            self::PAYMENT_XALYK => [
                'title' => 'Онлайн оплата Halyk',
                'desc' => 'Оплата по HALYK.',
                'icon' => 'images/icons/halyk.svg',
            ],
            self::PAYMENT_INVOICE => [
                'title' => 'Счет',
                'desc' => 'Оплата по счету по нашим банковским реквизитам',
                'icon' => 'images/icons/invoice.svg',
            ],
//            self::PAYMENT_KASPI => [
//                'title' => 'Kaspi QR',
//                'desc' => 'Lörem ipsum proktiga dur att endopod diling. Konack ultragon tåsam.',
//                'icon' => 'images/icons/kaspi.svg',
//            ],
        ];
    }

    public static function label(string $key): string
    {
        return self::labels()[$key]['title'] ?? '';
    }
}
