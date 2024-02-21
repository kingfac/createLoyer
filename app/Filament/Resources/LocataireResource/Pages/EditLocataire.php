<?php

namespace App\Filament\Resources\LocataireResource\Pages;

use Filament\Actions;
use App\Models\Occupation;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\LocataireResource;
use Illuminate\Database\Eloquent\Model;

class EditLocataire extends EditRecord
{
    protected static string $resource = LocataireResource::class;
    protected static ?string $title = 'Modifier locataire';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // protected function mutateFormDataBeforeSave(array $data): array
    // {
    //     //$data['user_id'] = auth()->id();
    //     //dd($data);
    //     $loyer = Occupation::where('id', $data['occupation_id'])->first();
    //     if($data['postnom'] == null){
    //         $data['postnom'] = "";
    //     }s
    //     if($data['prenom'] == null){
    //         $data['prenom'] = "";
    //     }
    //     if($data['nom'] == null){
    //         $data['nom'] = "";
    //     }
    //     // if($data['garantie'] == 0 && $data['nbr'] == null){
    //     //     Notification::make()
    //     //     ->warning()
    //     //     ->title("Validation données erreur")
    //     //     ->body("Garantie ou mois garantie doit être renseigné pour valider
    //     //     le formulaire")
    //     //     ->persistent()
            
    //     //     ->send();
    //     //     $this->halt();
    //     // }
    //     // else{
    //     //     if($data['nbr'] == null){
    //     //         $data['nbr'] = 0;
    //     //     }
    //     //     else{

    //     //         $data['garantie'] = ($loyer->montant * intval($data['nbr'])) ;
    //     //     }
    //     // }

    //     //dd($data);
    //     return $data;
    // }

    //protected function Edit
}
