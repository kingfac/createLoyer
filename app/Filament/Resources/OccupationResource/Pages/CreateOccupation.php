<?php

namespace App\Filament\Resources\OccupationResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\OccupationResource;

class CreateOccupation extends CreateRecord
{
    protected static string $resource = OccupationResource::class;
    protected static ?string $title = 'Créer occupation';

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('create');
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('Annuler')->url($this->getResource()::getUrl('index'));
    }
}
