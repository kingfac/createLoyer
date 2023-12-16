<?php

namespace App\Filament\Resources\DettesResource\Pages;

use App\Filament\Resources\DettesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDettes extends EditRecord
{
    protected static string $resource = DettesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
