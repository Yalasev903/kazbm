<?php

namespace App\Filament\Resources\ApplicationConsultationResource\Pages;

use App\Filament\Resources\ApplicationConsultationResource;
use Filament\Resources\Pages\ListRecords;

class ListApplicationConsultations extends ListRecords
{
    protected static string $resource = ApplicationConsultationResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
