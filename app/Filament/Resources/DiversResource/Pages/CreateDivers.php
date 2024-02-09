<?php

namespace App\Filament\Resources\DiversResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\DiversResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDivers extends CreateRecord
{
    protected static string $resource = DiversResource::class;


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('Annuler')->url($this->getResource()::getUrl('index'));
    }
}
