<?php

namespace App\Filament\Widgets;

use DateTime;
use Filament\Tables;
use App\Models\Loyer;
use App\Models\Divers;
use App\Models\Garantie;
use App\Models\Locataire;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Widgets\TableWidget as BaseWidget;

class RapportJournalier extends BaseWidget
{

    protected int | string | array $columnSpan = 'full';
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
}

