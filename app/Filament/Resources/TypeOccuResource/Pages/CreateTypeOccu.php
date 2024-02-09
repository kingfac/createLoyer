<?php

namespace App\Filament\Resources\TypeOccuResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\TypeOccuResource;

class CreateTypeOccu extends CreateRecord
{
    protected static string $resource = TypeOccuResource::class;
    protected static ?string $title = "Créer type occupation";

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('Annuler')->url($this->getResource()::getUrl('index'));
    }
}
