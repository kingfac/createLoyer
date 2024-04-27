<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Locataire;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class LocSoldeImpaye extends Component //implements HasForms, HasTable
{
   /*  use InteractsWithForms;
    use InteractsWithTable; */

    public $annee;
    public $mois;
    public $data;

    protected $listeners = ['m3a' => '$refresh'];

    public function render()
    {
        $this->data = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id', 'left outer')
        ->selectRaw('locataires.*')
        ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
        ->orderBy('locataires.id')
        ->get();
        $pdf = Pdf::loadHTML(Blade::render('inverse', ['data' => $this->data, 'label' => 'Locataires avec soldes impayés du mois de '.$this->mois, 'inverse' =>true]))->setPaper('a4', 'portrait');
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
        return view('livewire.loc-solde-impaye');
    }

    #[On('m3')] 
    public function update($mois, $annee)
    {
        // ...
        $this->annee = $annee;
        $this->mois = $mois;
        $this->dispatch('m3a');
    }




    
    
}


