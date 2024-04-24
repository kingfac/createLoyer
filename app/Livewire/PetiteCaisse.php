<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Loyer;
use App\Models\Depense;
use Livewire\Component;

class PetiteCaisse extends Component
{
    public $loyers;
    public $depenses;
    
    public function render()
    {
        return view('livewire.petite-caisse');
    }


    public function mount(){
        $this->loyers = Loyer::whereDate('created_at', Carbon::today())->get();
        $this->depenses = Depense::whereDate('created_at', Carbon::today())->get();
    }
}
