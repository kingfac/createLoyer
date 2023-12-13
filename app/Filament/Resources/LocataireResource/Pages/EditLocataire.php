<?php

namespace App\Filament\Resources\LocataireResource\Pages;

use App\Filament\Resources\LocataireResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLocataire extends EditRecord
{
    protected static string $resource = LocataireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
