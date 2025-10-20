<?php

namespace App\Filament\Settings;

use Spatie\LaravelSettings\Settings;
use App\Traits\ImageUpload;

class OblicHeroSettings extends Settings
{
    use ImageUpload;
    public array $title;
    public string $photo;

    public static function group(): string
    {
        return 'oblic_hero';
    }
}
