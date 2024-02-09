<?php

namespace App\Filament\Resources\GarantieResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\GarantieResource;

class CreateGarantie extends CreateRecord
{
    protected static string $resource = GarantieResource::class;


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('Annuler')->url($this->getResource()::getUrl('index'));
    }
}
