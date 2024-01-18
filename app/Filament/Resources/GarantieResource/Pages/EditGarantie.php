<?php

namespace App\Filament\Resources\GarantieResource\Pages;

use App\Filament\Resources\GarantieResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGarantie extends EditRecord
{
    protected static string $resource = GarantieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
