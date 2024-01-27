<?php

namespace App\Livewire;

use App\Models\Locataire;
use Livewire\Component;

class LocArrieres extends Component
{
    public $arrieres;

    public function render()
    {
        $this->remplir();
        return view('livewire.loc-arrieres');
    }

    public function remplir(){
        $this->arrieres = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id', 'left outer')
            //->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` ")
            ->selectRaw('locataires.*, loyers.annee, loyers.created_at, loyers.montant, loyers.mois')
            ->orderByRaw("locataires.id, loyers.annee, loyers.created_at")
            ->get();
    }
}
