<?php

namespace App\Filament\Resources\PatternResource\Pages;

use App\Filament\Resources\ProductPatternResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPattern extends EditRecord
{
    protected static string $resource = ProductPatternResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
