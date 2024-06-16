<?php

namespace App\Livewire;

use App\Models\Galerie;
use Livewire\Component;
use Livewire\Attributes\On;

class PrevsMens extends Component
{
    public $annee;
    public $mois;
    public $data;

    // protected $listeners = ['m10a' => '$refresh'];

    public function render()
    {
        return view('livewire.prevs-mens');
    }

    public function mount(){
        $this->remplir();
    }

    #[On('m10')] 
    public function update($mois, $annee)
    {
        $this->annee = $annee;
        $this->mois = $mois;
        $this->dispatch('m10a');
        $this->remplir();
    }

    public function remplir(){
        $this->data = Galerie::orderBy('id')->get();
    }

    public function imprimer(){
        dd('df');
    }
}
