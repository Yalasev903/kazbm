<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Settings\About\AdvantageSettings;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class AboutAdvantageSettingsPage extends SettingsPage
{

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $title = 'Преимущества';

    protected static ?string $navigationGroup = 'О компании';

    protected static ?string $slug = 'blocks/about/advantage';

    protected static string $settings = AdvantageSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Repeater::make('items')
                        ->maxItems(3)
                        ->label('Преимущества')
                        ->collapsed()
                        ->schema([
                            TextInput::make('title')
                                ->required()
                                ->label('Заголовок'),
                            Textarea::make('desc')
                                ->required()
                                ->label('Описание'),
                            FileUpload::make('image')
                                ->directory('advantage')
                                ->required()
                                ->image()
                                ->label('Фото')
                        ])
                ]),
            ]);
    }
}
