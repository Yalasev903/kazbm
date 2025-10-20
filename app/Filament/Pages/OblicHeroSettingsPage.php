<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Settings\OblicHeroSettings;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class OblicHeroSettingsPage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $title = 'Баннер (Облицовочный кирпич)';

    protected static ?string $navigationGroup = 'Облицовочный кирпич';

    protected static ?string $slug = 'blocks/oblic/banner';

    protected static string $settings = OblicHeroSettings::class;

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
