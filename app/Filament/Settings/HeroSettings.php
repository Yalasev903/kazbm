<?php

namespace App\Filament\Settings;

use App\Traits\ImageUpload;
use Spatie\LaravelSettings\Settings;

class HeroSettings extends Settings
{
    use ImageUpload;

    public array $title;
    public string $photo;

    public static function group(): string
    {
        return 'hero';
    }
}
