<?php

namespace App\Filament\Resources\PatternResource\Pages;

use App\Filament\Resources\ProductPatternResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePattern extends CreateRecord
{
    protected static string $resource = ProductPatternResource::class;
}
