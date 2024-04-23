<?php

namespace App\Filament\Resources\DepenseResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\DepenseResource;
use Illuminate\Support\Facades\Auth;

class CreateDepense extends CreateRecord
{
    protected static string $resource = DepenseResource::class;


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('Annuler')->url($this->getResource()::getUrl('index'));
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        //$data['user_id'] = auth()->id();
        $data['users_id'] = Auth::user()->id;
        return $data;
    }

}
