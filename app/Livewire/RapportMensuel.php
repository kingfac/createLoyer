<?php

namespace App\Livewire;

use App\Models\Galerie;
use App\Models\Garantie;
use App\Models\Locataire;
use DateTime;
use App\Models\Loyer;
use Livewire\Component;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class RapportMensuel extends Component implements HasForms,HasTable
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
        return view('livewire.rapport-mensuel');
    }

    public function table(Table $table): Table
    {
       
        
        return $table
            ->query(
                // ...
                Galerie::query()
                
            )
            ->columns([
                // ...
                TextColumn::make('nom')->label('Galerie'),
                TextColumn::make('Anciennes garanties')
                    ->default(function(Galerie $record){
                        $galerie = Galerie::where('id', $record->id)->first();
                        $locs = $galerie->occupations[0]->locataires;
                        // dd($locs);
                        
                        $gi = [];
                        foreach ($locs as $loc) {
                            // $start = new DateTime(NOW());
                            // $start->modify("-12 month");
                            // dd($start);

                            // $end_date= new DateTime(NOW());
                            // $end_date->modify("-1 month");
                            // dd($end);
                            $garanties = Garantie::where('locataire_id',$loc->id)->whereRaw("MONTH(created_at) < MONTH(NOW())")->sum('montant');
                            array_push($gi,$garanties);
                        }
                        
                        return array_sum($gi);
                    }),
                TextColumn::make('Nouvelles garanties')
                    ->default(function(Galerie $record){
                        $galerie = Galerie::where('id', $record->id)->first();
                        $locs = $galerie->occupations[0]->locataires;
                        // dd($locs);
                        
                        $gi = [];
                        foreach ($locs as $loc) {
                            $garanties = Garantie::where('locataire_id',$loc->id)->whereRaw("MONTH(created_at) = MONTH(NOW())")->sum('montant');
                            array_push($gi,$garanties);
                        }
                        
                        return array_sum($gi);
                    }),
                TextColumn::make('Dettes antérieures perçues')
                    ->default(function(Galerie $record){
                        $galerie = Galerie::where('id', $record->id)->first();
                        $locs = $galerie->occupations[0]->locataires;
                        $dettes=[];
                        // dd($locs);
                        //formater le mois de janvier to 01
                        foreach ($locs as $loc) {
                            # requette non terminee
                            $loyers = Loyer::where('locataire_id',$loc->id)->whereRaw("MONTH(created_at) = MONTH(NOW())")->sum('montant');
                            array_push($dettes,$loyers);
                        }
                        return array_sum($dettes);
                    }),
                TextColumn::make('Montant perçu Aout')
                    ->default(function(Galerie $record){

                        $galerie = Galerie::where('id', $record->id)->first();
                        $locs = $galerie->occupations[0]->locataires;
                        // dd($locs);
                        
                        $gi = [];
                        $dettes = [];
                        foreach ($locs as $loc) {
                            $garanties = Garantie::where('locataire_id',$loc->id)->whereRaw("MONTH(created_at) = MONTH(NOW())")->sum('montant');
                            array_push($gi,$garanties);

                            $loyers = Loyer::where('locataire_id',$loc->id)->whereRaw("MONTH(created_at) = MONTH(NOW())")->sum('montant');
                            array_push($dettes,$loyers);
                        }

                        $nouv_garanties = array_sum($gi);
                        $dets = array_sum($dettes);
                        
                        return $nouv_garanties+$dets;
                    }),
                TextColumn::make('Montant perçu Septembre'),
                TextColumn::make('Total perçu'),
                TextColumn::make('Montant attendu'),
                TextColumn::make('Montant non perçu'),
                TextColumn::make('Montant perçu Septembre'),
                TextColumn::make('Totaux de réalisation'),
                TextColumn::make('Sorties avec dettes'),
                TextColumn::make('Total dettes'),
                
                // TextColumn::make('montant')->label('Loyer payé')
                //     ->summarize(Sum::make()->money()->label('Total'))
                //     ->money(),
                // TextColumn::make('mois')->label('Mois'),
                // TextColumn::make('created_at')->label('Heure')->time(),
            ]);
    }

    public function remplir(){
        $this->mois = new DateTime();
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y');
        $this->annee = $this->mois->format('Y');
        $this->mois = $this->lesMois[$this->mois->format('m')];
        $this->data = Galerie::whereRaw("DAY(created_at) = DAY(NOW())")->get();
        $pdf = Pdf::loadHTML(Blade::render('journalier', ['data' => $this->data, 'label' => 'PAIEMENT JOURNALIER DU MOIS DE '.$this->mois]));
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
    }



}
