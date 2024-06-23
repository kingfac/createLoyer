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
use Filament\Actions\Action;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Concerns\InteractsWithTable;

class PaieJournalier extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $data;
    

    public $mois;
    public $label = 'paiement Journalier';
    public $lelo;
    public $locs;
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
  
    public function mount(){
        $this->lelo = new DateTime('now');
        $this->lelo = $this->lelo->format('d-m-Y');
        $this->locs = Locataire::where('actif',true)->orderBy('id', 'DESC')->get();
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
                TextColumn::make('ro')->label('Galerie')
                    ->default(function(Locataire $record){
                        return $record->occupation->galerie->nom.'-'.$record->occupation->galerie->num;
                    }),
                TextColumn::make('occupation.typeOccu.nom')->label('Occupation'),
              
                TextColumn::make("Loyer")->default(function(Locataire $record){
                    $moiss = [];
                    $current_data = NOW()->format('Y-m-d');
                    foreach (Loyer::where('locataire_id', $record->id)
                    ->whereRaw(" DATE(created_at) = '$current_data' ")
                    ->distinct('mois')
                    ->get(['mois','montant']) as $loy) {
                        # code...
                        $moiss[] = $loy->montant;
                    }
                    return array_sum($moiss).'$';
                }),

                TextColumn::make("Garantie")->default(function(Locataire $record){
                    $current_date = NOW()->format('Y-m-d');
                    
                    return Garantie::where(['locataire_id' => $record->id, 'restitution' => false])
                    ->whereRaw(" DATE(created_at) = '$current_date' ")
                    ->sum('montant');
                })
                ->summarize(Sum::make()->label('Total')->money())
                ->money(),
                
                TextColumn::make("DD")->label('Divers')->default(function(Locataire $record){
                    $current_date = NOW()->format('Y-m-d');
                    
                    return Divers::where('locataire_id', $record->id)
                    ->whereRaw(" DATE(created_at) = '$current_date' ")
                    ->sum('total');
                })
                // ->summarize(Sum::make()->label('Total')->money())
                ->money(),
                TextColumn::make("d")->label("Dettes")->default(function(Locataire $record){
                    $data = 0;
                    $current_date = NOW()->format('Y-m-d');
                    foreach (Loyer::where('locataire_id', $record->id)
                    ->whereRaw("DATE(created_at) = '$current_date' ")->get() as $loy) {
                        //dd($this->Mois2[$loy->mois], date('m'));
                        if($this->Mois2[$loy->mois] != date('m')){
                            $data += $loy->montant;
                        }
                    }
                    return $data;
                })->money(),
                TextColumn::make("Date")->default(date('j-M-Y'))
            ])->headerActions([
                ActionsAction::make('imprimer')
                    ->action(function(){
                        $lelo = new DateTime('now');
                        $lelo = $lelo->format('d-m-Y');
                        $data = Loyer::whereRaw("DAY(created_at) = DAY(NOW())")->get();
                        $pdf = Pdf::loadHTML(Blade::render('journalier', ['data' => $data, 'label' => 'Paiement journalier du '.$lelo]))->setPaper('a4', 'landscape');
                        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
                        return response()->download('../public/storage/pdf/doc.pdf');
                        
                    }),
            ]);
    }
    

    public function remplir(){
        $this->mois = new DateTime();
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y');
        $this->annee = $this->mois->format('Y');
        $this->mois = $this->lesMois[$this->mois->format('m')];
        // $this->data = Loyer::whereRaw("DAY(created_at) = DAY(NOW())")->get();
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y');
        $this->data = Loyer::whereRaw("DAY(created_at) = DAY(NOW())")->get();

        
    }



    
   
}
