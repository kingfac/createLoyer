<?php

namespace App\Livewire;


use Livewire\Component;

use App\Models\Locataire;
use App\Models\Loyer;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Support\Facades\DB;

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
            ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = 'Janvier' and `annee` = 2023)) as `somme`")
            ->orderBy('locataires.id')
            ->get();
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
