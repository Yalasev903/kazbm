<?php

namespace App\Filament\Settings\About;

use App\Traits\ImageUpload;
use Spatie\LaravelSettings\Settings;

class ProductSettings extends Settings
{
    use ImageUpload;

    public string $title;
    public string $description;
    public string $photo;
    public string $item_photo;
    public array $items;

    public static function group(): string
    {
        return 'about_product';
    }
}
