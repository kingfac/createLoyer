<?php

namespace App\Livewire;

use App\Models\Galerie;
use Livewire\Component;
use App\Models\Locataire;
use Livewire\Attributes\On;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;

class PrevMens extends Component
{
    public $annee;
    public $mois;
    public $data;

    protected $listeners = ['m10a' => '$refresh'];

    public function render()
    {
        return view('livewire.prev-mens');
    }

    public function mount(){
        $this->remplir();
    }

    #[On('m10')] 
    public function update($mois, $annee)
    {
        // ...
        $this->annee = $annee;
        $this->mois = $mois;
        $this->dispatch('m10a');
        $this->remplir();
    }

    public function remplir(){

        /* $this->data = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id')
            ->selectRaw('locataires.*')
            ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
            ->orderBy('locataires.id')
            ->get(); */
        $this->data = Galerie::orderBy('id')->get();
        
    }
}
