<?php

namespace App\Helpers;

class TelegramHelper
{
    /**
     * Форматирует номер телефона для кликабельной ссылки в Telegram
     */
    public static function formatPhoneForTelegram($phone)
    {
        // Удаляем все нецифровые символы, кроме +
        $cleaned = preg_replace('/[^\d+]/', '', $phone);

        // Если номер начинается с 8, заменяем на +7
        if (str_starts_with($cleaned, '8')) {
            $cleaned = '+7' . substr($cleaned, 1);
        }

        // Если номер без кода страны, добавляем +7
        if (strlen($cleaned) === 10 && !str_starts_with($cleaned, '+')) {
            $cleaned = '+7' . $cleaned;
        }

        return $cleaned;
    }

    /**
     * Создает кликабельную ссылку на номер в Telegram
     */
    public static function createPhoneLink($phone, $displayText = null)
    {
        $formattedPhone = self::formatPhoneForTelegram($phone);
        $display = $displayText ?: $formattedPhone;

        return "[$display](tel:$formattedPhone)";
    }
}
