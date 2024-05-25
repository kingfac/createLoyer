<?php

namespace App\Livewire;

use App\Models\Depense;
use App\Models\Divers;
use App\Models\Garantie;
use App\Models\Loyer;
use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Tables\Actions\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Livewire\Component;

class GrandeCaisse extends Component
{

    public $soldepetite; // lsolde de la petite caisse
    public $depenses; // depenses du jour J
    public $soldeA = 500000;

    public function render()
    {
        return view('livewire.grande-caisse');
    }

    public function mount(){
        $divers = Divers::whereDate('created_at', Carbon::today())->get();
        $totDivers=0;
        foreach ($divers as $diver) {
            $totDivers += $diver->qte*$diver->cu;
        }
        $this->soldepetite = 
            Loyer::whereDate('created_at', Carbon::today())->sum("montant")
        + Garantie::whereDate('created_at', Carbon::today())->where('restitution', false)->sum("montant")
        + $totDivers;
    
        foreach (Depense::whereDate('created_at', Carbon::today())->get() as $depense) {
            # code...
            $this->depenses += $depense->qte*$depense->cu;
        }
        
    }

 }
