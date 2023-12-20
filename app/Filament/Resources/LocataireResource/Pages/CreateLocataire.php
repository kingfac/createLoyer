<?php

namespace App\Filament\Resources\LocataireResource\Pages;

use Filament\Actions;
use App\Models\Occupation;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\LocataireResource;

class CreateLocataire extends CreateRecord
{
    protected static string $resource = LocataireResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('create');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        //$data['user_id'] = auth()->id();
        $loyer = Occupation::where('id', $data['occupation_id'])->first();
        $data['garantie'] = $loyer->montant * intval($data['mois']);
        //dd($data);
        return $data;
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('Annuler')->url($this->getResource()::getUrl('index'));
    }
}
