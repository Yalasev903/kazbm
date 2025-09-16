<?php

namespace App\Filament\Settings;

use Spatie\LaravelSettings\Settings;

class CalculatorSettings extends Settings
{

    public array $types;
    public array $methods;

    public static function group(): string
    {
        return 'calculator';
    }

    public function getType(int $type): string
    {
        return array_column($this->types, 'name')[$type] ?? '';
    }
}
