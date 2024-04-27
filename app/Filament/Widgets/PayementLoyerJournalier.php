<?php

namespace App\Filament\Widgets;

use DateTime;
use App\Models\User;
use Filament\Tables;
use App\Models\Loyer;
use App\Models\Divers;
use App\Models\Garantie;
use App\Models\Locataire;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Blade;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Widgets\TableWidget as BaseWidget;

class PayementLoyerJournalier extends BaseWidget
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
            Locataire::query()->where('actif',true)->orderBy('id', 'DESC')
            
        )
        ->columns([
            // ...
            TextColumn::make('noms')->label('Locataire'),
            TextColumn::make('un')->label('Galerie')
            ->default(function(Locataire $record){
                return $record->occupation->galerie->nom.'-'.$record->occupation->galerie->num;
            }),
            TextColumn::make('occupation.typeOccu.nom')->label('Occupation'),
            /* TextColumn::make('montant')->label('Loyer payé')
                ->summarize(Sum::make()->money()->label('Total'))
                ->money(), */
            /* TextColumn::make('mois')->label('Mois'),
            TextColumn::make('created_at')->label('Heure')->time(), */
            TextColumn::make("Loyer payé")->default(function(Locataire $record){
                $moiss = [];
                foreach (Loyer::where('locataire_id', $record->id)
                ->whereRaw('DATE(created_at) = CURDATE()')
                ->distinct('mois')
                ->get(['mois','montant','users_id']) as $loy) {
                    # code...
                    $nom = User::find($loy->users_id);
                    $moiss[] = $loy->mois.'('.$loy->montant.'$, '.'chez: '.$nom->name.')';
                }
                // dd($loyers);
                return $moiss;
            }),
            TextColumn::make("Garantie")->default(function(Locataire $record){
                $now = NOW()->format('Y-m-d');
                // dd($now);
                return Garantie::where(['locataire_id' => $record->id, 'restitution' => false])
                ->whereRaw(" DATE(created_at) = '$now' ")
                ->sum('montant');
            })
            // ->summarize(Sum::make())
            ->money(),
            TextColumn::make("DD")->label('Divers')->default(function(Locataire $record){
                
                return Divers::where('locataire_id', $record->id)
                ->whereRaw('DATE(created_at) = CURDATE()')
                ->sum('total');
            })->money(),
            TextColumn::make("d")->label("Dettes payées ")->default(function(Locataire $record){
                $data = 0;

                foreach (Loyer::where('locataire_id', $record->id)
                ->whereRaw('DATE(created_at) = CURDATE()')->get() as $loy) {
                    // dd($this->Mois2[$loy->mois], date('m'));
                    if($this->Mois2[$loy->mois] < date('m')){
                        $data += $loy->montant;
                    }
                }
                return $data;
                
            })->money(),
            // TextColumn::make("a")->label("Anticipatif")->default(function(Locataire $record){
            //     $data = 0;

            //     foreach (Loyer::where('locataire_id', $record->id)
            //     ->whereRaw('DATE(created_at) = CURDATE()')->get() as $loy) {
            //         // dd($this->Mois2[$loy->mois], date('m'));
            //         if($this->Mois2[$loy->mois] > date('m')){
            //             $data += $loy->montant;
            //         }
            //     }
            //     return $data;
                
            // })->money(),

            TextColumn::make("Date")->default(date('j-M-Y'))
        ])->headerActions([
            Action::make('Imprimer')
                ->action(function(){
                    $lelo = new DateTime('now');
                    $lelo = $lelo->format('d-m-Y');
                    $locs = Locataire::where('actif',true)->orderBy('id', 'DESC')->get();
                    // $data = Loyer::whereRaw("DAY(created_at) = DAY(NOW())")->get();
                    $pdf = Pdf::loadHTML(Blade::render('dash_journalier', ['locs' => $locs, 'label' => ' ,Garantie, Divers Journaliers  du '.$lelo]))->setPaper('a4', 'landscape');
                    // Pdf::setPaper($pdf,'paysage');
                    Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
                    return response()->download('../public/storage/pdf/doc.pdf');
                    
                })
        ]);
    }
}
