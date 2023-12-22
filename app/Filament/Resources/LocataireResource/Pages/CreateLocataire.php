<?php

namespace App\Filament\Resources\LocataireResource\Pages;

use Filament\Actions;
use App\Models\Occupation;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
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
        if($data['postnom'] == null){
            $data['postnom'] = "";
        }
        if($data['prenom'] == null){
            $data['prenom'] = "";
        }
        if($data['nom'] == null){
            $data['nom'] = "";
        }
        if($data['garantie'] == 0 && $data['mois'] == null){
            Notification::make()
            ->warning()
            ->title("Validation données erreur")
            ->body("Garantie ou mois garantie doit être renseigné pour valider
            le formulaire")
            ->persistent()
            
            ->send();
            $this->halt();
        }
        elseif($data['garantie'] == 0 && $data['mois'] != null){
            $data['garantie'] = $loyer->montant * intval($data['mois']);
        }

        //dd($data);
        return $data;
    }

    protected function beforeCreate():void{
        //dd($this->record->garantie);
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('Annuler')->url($this->getResource()::getUrl('index'));
    }
}
