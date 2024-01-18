<?php

namespace App\Filament\Resources\GarantieResource\Pages;

use App\Filament\Resources\GarantieResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGaranties extends ListRecords
{
    protected static string $resource = GarantieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
