<?php

namespace App\Filament\Settings\About;

use App\Traits\ImageUpload;
use Spatie\LaravelSettings\Settings;

class ProductSettings extends Settings
{
    use ImageUpload;

    public ?string $title = null;
    public ?string $description = null;
    public ?string $photo = null;
    public ?string $item_photo = null;
    public ?array $items = [];

    public static function group(): string
    {
        return 'about_product';
    }
}
