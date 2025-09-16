<?php

namespace App\Filament\Resources\ApplicationCallResource\Pages;

use App\Filament\Resources\ApplicationCallResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApplicationCall extends EditRecord
{
    protected static string $resource = ApplicationCallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
