<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Loyer;
use App\Models\Depense;
use App\Models\Divers;
use Livewire\Component;
use App\Models\Garantie;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;

class PetiteCaisse extends Component
{
    public $loyers;
    public $depenses;
    public $garanties;
    public $divers;
    public $total_dep=0;
    
    public function render()
    {
        return view('livewire.petite-caisse');
    }


    public function mount(){
        $this->loyers = Loyer::whereDate('created_at', Carbon::today())->get();
        $this->garanties = Garantie::whereDate('created_at',Carbon::today())->where('restitution',false)->sum('montant');
        $this->divers = Divers::whereDate('created_at',Carbon::today())->get();
        $this->depenses = Divers::whereDate('created_at', Carbon::today())->get();
        foreach ($this->depenses as $depense){
            $this->total_dep += $this->depenses->sum('qte') * $this->depenses->sum('cu');
        }
        //$this->garanties = Garantie::where('restitution',false)->whereDate('created_at', Carbon::today())->sum('montant')-Garantie::where('restitution', true)->whereDate('created_at', Carbon::today())->sum('montant')-Loyer::where('garantie', true)->whereDate('created_at', Carbon::today())->sum('montant');
    }

    public function imprimer(){

        $pdf = Pdf::loadHTML(Blade::render('petitecaisse', ['loyers' => $this->loyers,'divers' => $this->divers, 'garanties' => $this->garanties, 'label' => 'Petite caisse']))->setPaper('a4', 'portrait');
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
        return response()->download('../public/storage/pdf/doc.pdf');
    }
}
