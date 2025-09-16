<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Settings\HeroSettings;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class HeroSettingsPage extends SettingsPage
{

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group'; // bookmark-square

    protected static ?string $title = 'Баннер';

    protected static ?string $navigationGroup = 'Главная страница';

    protected static ?string $slug = 'blocks/home/banner';

    protected static string $settings = HeroSettings::class;
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('title.ru')
                        ->required()
                        ->label('Заголовок (Русский)'),
                    TextInput::make('title.kk')
                        ->required()
                        ->label('Заголовок (Казахский)'),
                    FileUpload::make('photo')
                        ->directory('settings')
                        ->required()
                        ->image()
                        ->label('Фото'),
                ]),
            ]);
    }

}
