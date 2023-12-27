<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Locataire;
use Livewire\Attributes\On;

class LocPaieRetard extends Component
{
    public $annee;
    public $mois;
    public $data;

    protected $listeners = ['m1a' => '$refresh'];
    public function render()
    {
        $this->data = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id')
        ->selectRaw('locataires.*')
        ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
        ->orderBy('locataires.id')
        ->get();
        return view('livewire.loc-paie-retard');
    }


    

    #[On('m1')] 
    public function update($mois, $annee)
    {
        // ...
        $this->annee = $annee;
        $this->mois = $mois;
        $this->dispatch('m1a');
    }
}
