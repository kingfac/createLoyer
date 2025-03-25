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
    public $rows ;
    public $start_page = 1;
    public $total_page;
    public $perPage = 25;
    public $perPageOptions = [25, 50, 100]; // Options for per page selection

    protected $listeners = ['m3a' => '$refresh'];

    public function render()
    {
        $this->rows = Locataire::where('actif', true)->count();
        $this->total_page = ceil($this->rows/$this->perPage);

        $this->data = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id', 'left outer')
        ->selectRaw('locataires.*')
        ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
        ->orderBy('locataires.id')
        ->skip(($this->start_page - 1) * $this->perPage)//
        ->take($this->perPage)//
        ->get();

        return view('livewire.loc-solde-impaye');
    }

    public function gotoPage($page)
    {
        $this->start_page = max(1, min($page, ceil($this->rows / $this->perPage)));
    }

    #[On('m3')]
    public function update($mois, $annee)
    {
        // ...
        $this->annee = $annee;
        $this->mois = $mois;
        $this->dispatch('m3a');
    }


    public function mount():void
    {
        $pdf = Pdf::loadHTML(Blade::render('inverse', [
            'data' =>  Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id', 'left outer')
            ->selectRaw('locataires.*')
            ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
            ->orderBy('locataires.id')
            ->get(),
            'label' => 'Locataires avec soldes impayés du mois de '.$this->mois, 'inverse' =>true]))->setPaper('a4', 'portrait');
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
    }





}


