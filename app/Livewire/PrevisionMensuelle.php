<?php

namespace App\Livewire;

use DateTime;
use App\Models\Loyer;
use App\Models\Galerie;
use Livewire\Component;
use App\Models\Garantie;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Actions\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class PrevisionMensuelle extends Component implements HasForms
{
    // use InteractsWithTable;
    use InteractsWithForms;

    public $data;
    

    public $mois;
    public $annee;
    public $lesMois = [
        '01' => 'Janvier',
        '02' => 'Février',
        '03' => 'Mars',
        '04' => 'Avril',
        '05' => 'Mais',
        '06' => 'Juin',
        '07' => 'Juillet',
        '08' => 'Aout',
        '09' => 'Septembre',
        '10' => 'Octobre',
        '11' => 'Novembre',
        '12' => 'Décembre'
    ];
    

    public function render()
    {
        // $this->remplir();
        return view('livewire.prevision-mensuelle');
        

    }
    
    public function imprimer(){
        
    }

}
