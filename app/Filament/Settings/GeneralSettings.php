<?php

namespace App\Filament\Settings;

use App\Helpers\Common;
use App\Traits\ImageUpload;
use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    use ImageUpload;

    public string $site_name;
    public ?string $desc;
    public string $phone;
    public ?string $email;

    public string $address_ru;
    public string $address_kk;
    public string $logo;
    public string $favicon;
    public ?string $logo_fixed;
    public ?string $map_link;
    public array $socials;

    public static function group(): string
    {
        return 'general';
    }

    public function getPhone(): string
    {
        return Common::getPhone($this->phone) ?: '';
    }
}
