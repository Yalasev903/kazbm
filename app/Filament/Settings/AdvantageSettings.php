<?php

namespace App\Filament\Settings;

use Spatie\LaravelSettings\Settings;

class AdvantageSettings extends Settings
{

    public array $items;

    public static function group(): string
    {
        return 'advantage';
    }
}
