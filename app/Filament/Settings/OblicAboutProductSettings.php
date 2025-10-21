<?php

namespace App\Filament\Settings;

use Spatie\LaravelSettings\Settings;
use App\Traits\ImageUpload;

class OblicAboutProductSettings extends Settings
{
    use ImageUpload;

    public string $title;
    public string $description;
    public string $photo;
    public string $item_photo;
    public array $items;
    

    public static function group(): string
    {
        return 'oblic_about_product';
    }
}
