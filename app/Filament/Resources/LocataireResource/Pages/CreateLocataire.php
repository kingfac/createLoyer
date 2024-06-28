<?php

namespace App\Filament\Resources\LocataireResource\Pages;

use Filament\Actions;
use App\Models\Occupation;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\LocataireResource;
use App\Models\Garantie;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CreateLocataire extends CreateRecord
{
    protected static string $resource = LocataireResource::class;
    protected static ?string $title = 'Créer locataire';

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        //$data['user_id'] = auth()->id();
        $data['garantie'] = null;


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
        if($data['garantie'] == 0 && $data['nbr'] == null){
            /* Notification::make()
            ->warning()
            ->title("Validation données erreur")
            ->body("Garantie ou mois garantie doit être renseigné pour valider
            le formulaire")
            ->persistent()
            
            ->send();
            $this->halt(); */
            //Rien A faire pena awa
            $data['nbr'] = 0;
            $data['garantie'] = 0;
        }
        elseif($data['garantie'] == 0 && $data['nbr'] != null){
            $data['garantie'] = $loyer->montant * intval($data['nbr']);
        }
        elseif($data['garantie'] != 0 && $data['nbr'] == null){
            $data['nbr'] = 0;
        }

        //dd($data);
        // dd($data);
        return $data;
    }

    protected function beforeCreate():void{
        // dd($this->record);
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('Annuler')->url($this->getResource()::getUrl('index'));
    }

    protected function afterCreate(): void
    {
        $code='';
        //generer le code de confirmation
        $user_ramdom_key1 = "012386746458446454474846545343535366876575756456789";
        srand((double)microtime()*time());
        for($i=1; $i<=6; $i++) {
            $code .= $user_ramdom_key1[rand()%strlen($user_ramdom_key1)];
        }
        // Runs after the form fields are saved to the database.
        // dd($this->record);
        $this->record->update(['matricule' => $code]);
        // dd('reuo');
        if($this->record->garantie > 0){
            Garantie::create([
                'locataire_id' => $this->record->id, 
                'montant' => $this->record->garantie,
                'users_id' => Auth::user()->id,
            ]);
        }
    }

   
}
