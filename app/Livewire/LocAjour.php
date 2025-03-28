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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LocAjourExport;

class LocAjour extends Component implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;

    public $annee;
    public $mois;
    public $data;
    public $rows ;
    public $start_page = 1;
    public $total_page;
    public $perPage = 25;
    public $perPageOptions = [25, 50, 100]; // Options for per page selection

    protected $listeners = ['m0a' => '$refresh'];

    public function render()
    {

        $this->remplir();

        return view('livewire.loc-ajour');
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Locataire::where('locataires.actif',true)->join('loyers', 'loyers.locataire_id', '=', 'locataires.id')
                ->selectRaw('
                    locataires.id,
                    locataires.noms,
                    locataires.occupation_id')
                ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
                ->groupBy('locataires.id', 'locataires.noms', 'locataires.occupation_id')
                ->orderBy('locataires.id')
        )
        // ->modifyQueryUsing(function ($query) {
        //     return $query->havingRaw('somme = occupation.montant'); // Exclure ceux qui ont tout payé
        // })
        ->columns([
            TextColumn::make('noms'),
            TextColumn::make('somme'),
            TextColumn::make('occupation.nom'),
        ]);
    }

    public function mount():void
    {
        //$this->exportExcel();
    }



    #[On('m0')]
    public function update($mois, $annee)
    {
        // ...
        $this->annee = $annee;
        $this->mois = $mois;
        $this->remplir();
        $this->dispatch('m0a');

    }

    public function remplir()
    {
        $this->rows = Locataire::where('actif', true)->count();
        //if($this->offset > 4) dd($this->offset);

        $this->total_page = ceil($this->rows/$this->perPage);

        $this->data = Locataire::where('actif',true)->join('loyers', 'loyers.locataire_id', '=', 'locataires.id')
            ->selectRaw('locataires.*')
            ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
            ->orderBy('locataires.id')
            ->skip(($this->start_page - 1) * $this->perPage)//
            ->take($this->perPage)//
            ->get();

        // $pdf = Pdf::loadHTML(Blade::render('locajour', [
        //     'data' => Locataire::where('actif',true)->join('loyers', 'loyers.locataire_id', '=', 'locataires.id')
        //     ->selectRaw('locataires.*')
        //     ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
        //     ->orderBy('locataires.id')
        //     ->get(),
        //     'label' => 'Locataires à jours de '.$this->mois.' '.$this->annee]))->setPaper('a4', 'landscape');
        // Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
        $this->total_page = ceil($this->data->count()/$this->perPage);
    }

    public function gotoPage($page)
    {
        $this->start_page = max(1, min($page, ceil($this->rows / $this->perPage)));
    }

    public function exportExcel()
    {
        return Excel::download(new LocAjourExport($this->mois, $this->annee), 'LocAjour.xlsx');
    }

}
