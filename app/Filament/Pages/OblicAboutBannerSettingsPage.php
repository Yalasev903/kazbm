<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Settings\OblicAboutBannerSettings;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class OblicAboutBannerSettingsPage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $title = 'Баннер';

    protected static ?string $navigationGroup = 'О компании (облицовочный кирпич)';

    protected static ?string $slug = 'blocks/oblic-company/banner';

    protected static string $settings = OblicAboutBannerSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('title_ru')
                        ->required()
                        ->label('Заголовок на русском'),
                    TextInput::make('title_kk')
                        ->required()
                        ->label('Заголовок на казахском'),
                    FileUpload::make('photo')
                        ->directory('oblic_about')
                        ->required()
                        ->image()
                        ->label('Фото'),
                    TextInput::make('sub_title')
                        ->required()
                        ->label('Подзаголовок'),
                    Textarea::make('desc_ru')
                        ->required()
                        ->label('Описание на русском'),
                    Textarea::make('desc_kk')
                        ->required()
                        ->label('Описание на казахском'),
                    FileUpload::make('bg_image')
                        ->directory('oblic_about')
                        ->required()
                        ->image()
                        ->label('Фоновое изображение'),
                ]),
            ]);
    }
}
