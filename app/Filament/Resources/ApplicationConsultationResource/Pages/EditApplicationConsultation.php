<?php

namespace App\Filament\Resources\ApplicationConsultationResource\Pages;

use App\Filament\Resources\ApplicationConsultationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApplicationConsultation extends EditRecord
{
    protected static string $resource = ApplicationConsultationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
