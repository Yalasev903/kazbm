<?php

namespace App\Filament\Settings\About;

use Spatie\LaravelSettings\Settings;

class AdvantageSettings extends Settings
{

    public array $items;

    public static function group(): string
    {
        return 'about_advantage';
    }
}
