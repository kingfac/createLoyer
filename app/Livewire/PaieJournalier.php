<?php

namespace App\Livewire;

use App\Models\Divers;
use App\Models\Garantie;
use App\Models\Locataire;
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
    public $Mois2 = [
        'Janvier' => '01',
        'Février' => '02',
        'Mars' => '03',
        'Avril' => '04',
        'Mais' => '05',
        'Juin' => '06',
        'Juillet' => '07',
        'Aout' => '08',
        'Septembre' => '09',
        'Octobre' => '10',
        'Novembre' => '11',
        'Décembre' => '12'
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
                Locataire::query()
                
            )
            ->columns([
                // ...
                TextColumn::make('noms')->label('Locataire'),
                TextColumn::make('occupation.galerie.nom')->label('Galerie'),
                TextColumn::make('occupation.typeOccu.nom')->label('Occupation'),
                /* TextColumn::make('montant')->label('Loyer payé')
                    ->summarize(Sum::make()->money()->label('Total'))
                    ->money(), */
                /* TextColumn::make('mois')->label('Mois'),
                TextColumn::make('created_at')->label('Heure')->time(), */
                TextColumn::make("Periode")->default(function(Locataire $record){
                    $moiss = [];
                    foreach (Loyer::where('locataire_id', $record->id)
                    ->whereRaw('DATE(created_at) = CURDATE()')
                    ->distinct('mois')
                    ->get('mois') as $loy) {
                        # code...
                        $moiss[] = $loy->mois;
                    }
                    return $moiss;
                }),
                TextColumn::make("Garantie")->default(function(Locataire $record){
                    
                    return Garantie::where('locataire_id', $record->id)
                    ->whereRaw('DATE(created_at) = CURDATE()')
                    ->sum('montant');
                })
                ->money(),
                TextColumn::make("DD")->label('Divers')->default(function(Locataire $record){
                    
                    return Divers::where('locataire_id', $record->id)
                    ->whereRaw('DATE(created_at) = CURDATE()')
                    ->sum('total');
                })->money(),
                TextColumn::make("d")->label("Dettes")->default(function(Locataire $record){
                    $data = 0;

                    foreach (Loyer::where('locataire_id', $record->id)
                    ->whereRaw('DATE(created_at) = CURDATE()')->get() as $loy) {
                        //dd($this->Mois2[$loy->mois], date('m'));
                        if($this->Mois2[$loy->mois] != date('m')){
                            $data += $loy->montant;
                        }
                    }
                    return $data;
                })->money(),
                TextColumn::make("Date")->default(date('j-M-Y'))
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
