<?php

namespace App\Filament\Resources\DepenseResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\DepenseResource;

class CreateDepense extends CreateRecord
{
    protected static string $resource = DepenseResource::class;


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('create');
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('Annuler')->url($this->getResource()::getUrl('index'));
    }
}
