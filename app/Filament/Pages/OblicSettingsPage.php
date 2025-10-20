<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class OblicSettingsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationGroup = 'Настройки сайта';

    protected static ?string $title = 'Облицовочный кирпич';

    protected static string $view = 'filament.pages.oblic-settings';

    protected static ?string $navigationLabel = 'Облицовочный кирпич';

    protected static ?int $navigationSort = 2;
}
