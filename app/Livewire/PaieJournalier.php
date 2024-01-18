<?php

namespace App\Livewire;

use DateTime;
use App\Models\Loyer;
use Livewire\Component;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Concerns\InteractsWithTable;

class PaieJournalier extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
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
        $this->remplir();
        return view('livewire.paie-journalier');
    }

    public function table(Table $table): Table
    {
       
        
        return $table
            ->query(
                // ...
                Loyer::query()->whereRaw("DAY(created_at) = DAY(NOW())")
                
            )
            ->columns([
                // ...
                TextColumn::make('locataire.noms')->label('Locataire'),
                TextColumn::make('locataire.occupation.galerie.nom')->label('Galerie'),
                TextColumn::make('locataire.occupation.typeOccu.nom')->label('Occupation'),
                TextColumn::make('montant')->label('Loyer payé')
                    ->summarize(Sum::make()->money()->label('Total'))
                    ->money(),
                TextColumn::make('mois')->label('Mois'),
                TextColumn::make('created_at')->label('Heure')->time(),
            ]);
    }

    public function remplir(){
        $this->mois = new DateTime();
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y');
        $this->annee = $this->mois->format('Y');
        $this->mois = $this->lesMois[$this->mois->format('m')];
        $this->data = Loyer::whereRaw("DAY(created_at) = DAY(NOW())")->get();
        $pdf = Pdf::loadHTML(Blade::render('journalier', ['data' => $this->data, 'label' => 'PAIEMENT JOURNALIER DU MOIS DE '.$this->mois]));
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
    }



    
   
}
