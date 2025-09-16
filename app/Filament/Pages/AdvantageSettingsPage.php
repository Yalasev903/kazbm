<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Settings\AdvantageSettings;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class AdvantageSettingsPage extends SettingsPage
{

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $title = 'Преимущества';

    protected static ?string $navigationGroup = 'Главная страница';

    protected static ?string $slug = 'blocks/home/advantage';

    protected static string $settings = AdvantageSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Repeater::make('items')
                        ->maxItems(4)
                        ->label('Преимущества')
                        ->collapsed()
                        ->schema([
                            TextInput::make('title_ru')
                                ->required()
                                ->label('Заголовок на русском'),
                            TextInput::make('title_kk')
                                ->required()
                                ->label('Заголовок на казахском'),
                            Textarea::make('desc_ru')
                                ->required()
                                ->label('Описание на русском'),
                            Textarea::make('desc_kk')
                                ->required()
                                ->label('Описание на казахском'),
                            FileUpload::make('image')
                                ->directory('advantage')
                                ->required()
                                ->image()
                                ->label('Фото'),
                            FileUpload::make('small_image')
                                ->directory('advantage')
                                ->required()
                                ->image()
                                ->label('Фото (в моб. версии)')
                        ])
                    ]),
            ]);
    }
}
