<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Loyer;
use App\Models\Depense;
use App\Models\Garantie;
use Livewire\Component;

class PetiteCaisse extends Component
{
    public $loyers;
    public $depenses;
    public $garanties;
    
    public function render()
    {
        return view('livewire.petite-caisse');
    }


    public function mount(){
        $this->loyers = Loyer::whereDate('created_at', Carbon::today())->get();
        $this->depenses = Depense::whereDate('created_at', Carbon::today())->get();
        $this->garanties = Garantie::where('restitution',false)->whereDate('created_at', Carbon::today())->sum('montant')-Garantie::where('restitution', true)->whereDate('created_at', Carbon::today())->sum('montant')-Loyer::where('garantie', true)->whereDate('created_at', Carbon::today())->sum('montant');
    }
}
