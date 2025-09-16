<?php

namespace App\Filament\Resources\ApplicationCallResource\Pages;

use App\Filament\Resources\ApplicationCallResource;
use Filament\Resources\Pages\ListRecords;

class ListApplicationCalls extends ListRecords
{
    protected static string $resource = ApplicationCallResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
