<?php

namespace App\Livewire;


use App\Models\Loyer;

use Livewire\Component;
use App\Models\Locataire;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class LocAjour extends Component //implements HasForms, HasTable
{

    /* use InteractsWithTable;
    use InteractsWithForms; */

    public $annee;
    public $mois;
    public $data;

    protected $listeners = ['m0a' => '$refresh'];

    public function render()
    {

        return view('livewire.loc-ajour');
    }

    public function mount(){
        $this->remplir();
    }

    #[On('m0')] 
    public function update($mois, $annee)
    {
        // ...
        $this->annee = $annee;
        $this->mois = $mois;
        $this->dispatch('m0a');
        $this->remplir();
        
    }

    public function remplir(){

        $this->data = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id')
            ->selectRaw('locataires.*')
            ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
            ->orderBy('locataires.id')
            ->get();
        $pdf = Pdf::loadHTML(Blade::render('locajour', ['data' => $this->data, 'label' => 'Locataires à jours de '.$this->mois.' '.$this->annee]));
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
    }

    
    
   
}
