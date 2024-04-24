<?php

namespace App\Livewire;

use App\Models\Depense;
use App\Models\Loyer;
use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Tables\Actions\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Livewire\Component;

class GrandeCaisse extends Component
{

    public $loyersA; // loyers du jour avant (yesterday)
    public $depensesA; // depenses du jour avant
    public $depenses; // depenses du jour J

    public function render()
    {
        return view('livewire.grande-caisse');
    }

    public function mount(){
        $this->loyersA = Loyer::whereDate('created_at', Carbon::yesterday())->get();
        $this->depensesA = Depense::whereDate('created_at', Carbon::yesterday())->get();
        $this->depenses = Depense::whereDate('created_at', Carbon::today())->get();
        
    }

    // public function form(Table $table): Table{
    //     return $table
    //         ->query(Garantie::where('restitution', true)->where('montant','<', 0))
    //         ->columns([
    //             TextColumn::make('locataire.noms'),
    //             TextColumn::make('locataire.occupation.galerie.nom'),
    //             TextColumn::make('locataire.occupation.typeOccu.nom')
    //                 ->label('Type occupation'),
    //             TextColumn::make('locataire.num_occupation')
    //                 ->label('Numéro occupation'),
    //             TextColumn::make('montant')
    //                 ->label('Dette'),
    //             TextColumn::make('created_at')
    //                 ->label('Date de sortie')
    //         ]);
    // }
}
