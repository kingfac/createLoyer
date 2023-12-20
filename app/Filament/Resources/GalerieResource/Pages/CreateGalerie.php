<?php

namespace App\Filament\Resources\GalerieResource\Pages;

use App\Filament\Resources\GalerieResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateGalerie extends CreateRecord
{
    protected static string $resource = GalerieResource::class;
    //protected static bool $Create = false;
    
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
