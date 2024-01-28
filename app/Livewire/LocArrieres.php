<?php

namespace App\Livewire;

use App\Models\Locataire;
use Livewire\Component;

class LocArrieres extends Component
{
    public $locataires;

    public function render()
    {
        $this->remplir();
        return view('livewire.loc-arrieres');
    }

    public function remplir(){
        $this->locataires = Locataire::all();
    }
}
