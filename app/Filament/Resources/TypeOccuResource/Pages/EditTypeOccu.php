<?php

namespace App\Filament\Resources\TypeOccuResource\Pages;

use App\Filament\Resources\TypeOccuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTypeOccu extends EditRecord
{
    protected static string $resource = TypeOccuResource::class;
    protected static ?string $title = "Modifier Type occupation";

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
