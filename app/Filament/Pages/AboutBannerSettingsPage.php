<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Settings\About\BannerSettings;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class AboutBannerSettingsPage extends SettingsPage
{

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $title = 'Баннер';

    protected static ?string $navigationGroup = 'О компании';

    protected static ?string $slug = 'blocks/about/banner';

    protected static string $settings = BannerSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make('Блок №1')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->label('Заголовок'),
                        FileUpload::make('bg_image')
                            ->directory('settings')
                            ->required()
                            ->image()
                            ->label('Фото'),
                ]),

                Card::make('Блок №2')
                    ->schema([
                        TextInput::make('sub_title')
                            ->required()
                            ->label('Заголовок'),
                        Textarea::make('desc_kk')
                            ->required()
                            ->label('Краткое описание'),
                        Textarea::make('desc_ru')
                            ->required()
                            ->label('Краткое описание'),
                        FileUpload::make('photo')
                            ->directory('settings')
                            ->required()
                            ->image()
                            ->label('Фото'),
                    ]),
            ]);
    }
}
