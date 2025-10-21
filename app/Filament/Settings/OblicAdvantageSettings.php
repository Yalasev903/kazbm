<?php

namespace App\Filament\Settings;

use Spatie\LaravelSettings\Settings;

class OblicAdvantageSettings extends Settings
{
    public array $items;

    public static function group(): string
    {
        return 'oblic_advantage';
    }
     // Добавьте эти методы:
    public function getWebpFormat($field, $value = null)
    {
        if (is_null($value)) {
            $value = $this->{$field};
        }

        if (!$value) {
            return '';
        }

        $path = pathinfo($value);
        return $path['dirname'] . '/' . $path['filename'] . '.webp';
    }

    public function getRealFormat($field, $value = null)
    {
        if (is_null($value)) {
            $value = $this->{$field};
        }

        return $value;
    }
}
