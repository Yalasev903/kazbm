<?php

namespace App\Filament\Resources\PatternResource\Pages;

use App\Filament\Resources\ProductPatternResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPatterns extends ListRecords
{
    protected static string $resource = ProductPatternResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
