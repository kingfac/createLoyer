<?php

namespace App\Livewire;


use App\Models\Loyer;

use Livewire\Component;
use App\Models\Locataire;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $this->data = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id')
            ->selectRaw('locataires.*')
            ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
            ->orderBy('locataires.id')
            ->get();
        $pdf = Pdf::loadHTML(Blade::render('evolution', ['data' => $this->data, 'label' => 'LOCATAIRE À JOUR DU MOIS DE '.$this->mois]));
        $pdf->save(public_path().'/pdf/doc.pdf');
        return view('livewire.loc-ajour');
    }

    #[On('m0')] 
    public function update($mois, $annee)
    {
        // ...
        $this->annee = $annee;
        $this->mois = $mois;
        $this->dispatch('m0a');
    }

    
    
   
}
