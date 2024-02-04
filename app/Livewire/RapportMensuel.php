<?php

namespace App\Livewire;

use DateTime;
use App\Models\Loyer;
use App\Models\Galerie;
use Livewire\Component;
use App\Models\Garantie;
use App\Models\Locataire;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
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
    public function getSomme($gal){
        $montantOccupation = 0;
        foreach ($gal->occupations as $occ) {
            $montantOccupation += $occ->montant;
        }
        $montantOccupation *= count($occ->locataires);
        return $montantOccupation;
    }

    public function getLoyerGalerie ($gal,$mois){
        $loyerGalerie = 0;
        foreach($gal->occupations as $occ){
            foreach($occ->locataires as $loc){
                foreach($loc->loyers as $loy){
                    if($loy->mois == $mois){
                        $loyerGalerie += $loy->montant;
                    }
                }
            }
        }
        return $loyerGalerie;
    }
    public function getTauxRealisation($gal,$mois){
        if($this->getLoyerGalerie($gal,$mois)!=0){
            return ($this->getSomme($gal)*100)/$this->getLoyerGalerie($gal,$mois);
        }
        return 0;
    }

    protected $listeners = ['m11a' => '$refresh'];


    #[On('m11')] 
    public function update($mois, $annee)
    {
        // ...
        // dd($this->annee);
        $this->annee = $annee;
        $this->mois = $mois;
        // dd($this->mois);
        $this->dispatch('m11a');
        // $this->remplir();
        
    }
    
    public function render()
    {
        // $this->remplir();
        // dd($this->mois);
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
                        $occups = $galerie->occupations;
                        $locs=[];
                        $mois = intval($this->Mois2[$this->mois]);
                        foreach($occups as $occup){
                            array_push($locs, $occup->locataires);
                        }
                        $somme=[];
                        for($i=0; $i < count($locs); $i++){
                            $garanties = Garantie::where('locataire_id',$locs[$i][0]->id)->whereRaw("MONTH(created_at) < $mois and restitution=false and YEAR(created_at) <= $this->annee");
                            array_push($somme,$garanties->sum('montant'));
                        }
                        // $locs = Locataire::where('occupation_id', )->get();
                        // foreach ($locs as $loc) {
                        //     $start = new DateTime(NOW());
                        //     $start->modify("-12 month");

                        //     $end_date= new DateTime(NOW());
                        //     $end_date->modify("-1 month");
                        //     array_push($gi,$garanties);
                        // }
                        
                        return array_sum($somme);
                    })                     
                    ->suffix(' $'),

                TextColumn::make('Nouvelles garanties')
                    ->default(function(Galerie $record){
                        $mois = intval($this->Mois2[$this->mois]);
                        $galerie = Galerie::where('id', $record->id)->first();
                        $occups = $galerie->occupations;

                        $locs=[];
                        foreach($occups as $occup){
                            array_push($locs, $occup->locataires);
                        }
                        $somme=[];
                        for($i=0; $i < count($locs); $i++){
                            // $garanties = Garantie::where('locataire_id',$locs[$i][0]->id)->whereRaw("MONTH(created_at) < $mois and restitution=false and YEAR(created_at) <= $this->annee");
                            // array_push($somme,$garanties->sum('montant'));
                            $garanties = Garantie::where('locataire_id',$locs[$i][0]->id)->whereRaw("MONTH(created_at) = $mois and restitution=0 and YEAR(created_at) = $this->annee")->sum('montant');
                            array_push($somme,$garanties);
                        }

                        return array_sum($somme);
                    })                    
                    ->suffix(' $'),

                TextColumn::make('Dettes antérieures perçues')
                    ->default(function(Galerie $record){
                        $galerie = Galerie::where('id', $record->id)->first();
                        $mois = intval($this->Mois2[$this->mois]);

                        $occups = $galerie->occupations;
                        $locs=[];
                        foreach($occups as $occup){
                            array_push($locs, $occup->locataires);
                        }
                        $somme=[];
                        for($i=0; $i < count($locs); $i++){
                            //ne doit pas recupere paiement anticipatif
                            $loyers = Loyer::where('locataire_id',$locs[$i][0]->id)->whereRaw(" mois != '$this->mois' and YEAR(created_at) = $this->annee ")->sum('montant');
                            array_push($somme,$loyers);
                           
                        }

                        return array_sum($somme);

                    
                    })                    
                    ->suffix(' $'),

                TextColumn::make('Montant(loyer) perçu Ce mois')
                    ->default(function(Galerie $record){

                        $galerie = Galerie::where('id', $record->id)->first();
                        $mois = intval($this->Mois2[$this->mois]);

                        $occups = $galerie->occupations;
                        $locs=[];
                        foreach($occups as $occup){
                            array_push($locs, $occup->locataires);
                        }
                        $somme=[];
                        for($i=0; $i < count($locs); $i++){
                            //ne doit pas recupere paiement anticipatif
                            $loyers = Loyer::where('locataire_id',$locs[$i][0]->id)->whereRaw("mois = '$this->mois' and annee = $this->annee   ")->sum('montant');
                            array_push($somme,$loyers);
                           
                        }

                        return array_sum($somme);

                        // $galerie = Galerie::where('id', $record->id)->first();
                        // $locs = $galerie->occupations[0]->locataires;
                        // // dd($locs);
                        
                        // $gi = [];
                        // $dettes = [];
                        // foreach ($locs as $loc) {
                        //     $garanties = Garantie::where('locataire_id',$loc->id)->whereRaw("MONTH(created_at) = MONTH(NOW())")->sum('montant');
                        //     array_push($gi,$garanties);

                        //     $loyers = Loyer::where('locataire_id',$loc->id)->whereRaw("MONTH(created_at) = MONTH(NOW())")->sum('montant');
                        //     array_push($dettes,$loyers);
                        // }

                        // $nouv_garanties = array_sum($gi);
                        // $dets = array_sum($dettes);
                        
                        // return $nouv_garanties+$dets;
                    })                     
                    ->suffix(' $'),
                TextColumn::make('Montant perçu au mois suivant')
                    ->default(function(Galerie $record){
                            $galerie = Galerie::where('id', $record->id)->first();
                            $mois = intval($this->Mois2[$this->mois]);

                            $occups = $galerie->occupations;
                            $locs=[];
                            foreach($occups as $occup){
                                array_push($locs, $occup->locataires);
                            }
                            // dd($locs);
                            $somme=[];
                            // $mois_suivant =  $this->lesMois['0'.$mois +1];
                            // dd($mois_suivant);
                            $mois_suivant = '0'.$mois +1;
                            for($i=0; $i < count($locs); $i++){
                                //ne doit pas recupere paiement anticipatif
                                $loyers = Loyer::where('locataire_id',$locs[$i][0]->id)->whereRaw(" MONTH(created_at) = $mois_suivant")->sum('montant');
                                // dd($loyers);
                                array_push($somme,$loyers);
                            
                            }
                            return array_sum($somme);
                        })
                        ->suffix(' $'),

                TextColumn::make('Total perçu')
                ->default(function(Galerie $record){
                    $galerie = Galerie::where('id', $record->id)->first();
                    $mois = intval($this->Mois2[$this->mois]);

                    $occups = $galerie->occupations;
                    $locs=[];
                    foreach($occups as $occup){
                        array_push($locs, $occup->locataires);
                    }
                    // dd($locs);
                    $somme=[];
                    $somme1=[];
                    $somme2=[];
                    $somme3=[];
                    $somme4=[];
                    // $mois_suivant =  $this->lesMois['0'.$mois +1];
                    // dd($mois_suivant);
                    $mois_suivant = '0'.$mois +1;
                    for($i=0; $i < count($locs); $i++){
                        //ne doit pas recupere paiement anticipatif
                        $loyers1 = Loyer::where('locataire_id',$locs[$i][0]->id)->whereRaw(" MONTH(created_at) = $mois_suivant")->sum('montant');
                        // dd($loyers);
                        array_push($somme1,$loyers1);
                        $loyers2 = Loyer::where('locataire_id',$locs[$i][0]->id)->whereRaw("mois = '$this->mois' and annee = $this->annee   ")->sum('montant');
                        // dd($loyers);
                        array_push($somme2,$loyers2);
                        $loyers3 = Loyer::where('locataire_id',$locs[$i][0]->id)->whereRaw(" mois != '$this->mois' and YEAR(created_at) = $this->annee ")->sum('montant');
                        // dd($loyers);
                        array_push($somme3,$loyers3);
                        $garanties = Garantie::where('locataire_id',$locs[$i][0]->id)->whereRaw("MONTH(created_at) = $mois and restitution=0 and YEAR(created_at) = $this->annee")->sum('montant');
                        array_push($somme4,$garanties);
                       
                    
                    }
                    $tot1 = array_sum($somme1);
                    $tot2 = array_sum($somme2);
                    $tot3 = array_sum($somme3);
                    $tot4 = array_sum($somme4);
                    
                    return ($tot1+$tot2+$tot3+$tot4);

                
                })
                ->suffix(' $'),
                TextColumn::make('Montant attendu')
                    ->default(function(Galerie $record){
                        return $this->getSomme(Galerie::where('id',$record->id)->first());
                    })
                    ->suffix(' $'),
                
                TextColumn::make('Montant non perçu')
                    ->default(function (Galerie $record){
                        return $this->getLoyerGalerie(Galerie::where('id',$record->id)->first(),$this->mois)-$this->getSomme(Galerie::where('id',$record->id)->first());
                    })
                    ->suffix(' $'),

                TextColumn::make('Taux de réalisation')
                    ->default(function(Galerie $record){
                        
                        return $this->getTauxRealisation(Galerie::where('id',$record->id)->first(),$this->mois);
                    })
                    ->suffix(' %'),
                TextColumn::make('Sorties avec dettes')
                    ->default(function(Model $record){
                        $value = Garantie::where('restitution',true)->where('montant','<',0)->count();
                        return $value;
                    }),
                    /*  
                    TextColumn::make('Montant perçu Septembre'),
                TextColumn::make('Total dettes'), */
                
                // TextColumn::make('montant')->label('Loyer payé')
                //     ->summarize(Sum::make()->money()->label('Total'))
                //     ->money(),
                // TextColumn::make('mois')->label('Mois'),
                // TextColumn::make('created_at')->label('Heure')->time(),
            ]);
    }

    public function remplir(){
        // $this->mois = new DateTime();
        // $lelo = new DateTime('now');
        // $lelo = $lelo->format('d-m-Y');
        $this->annee = $this->mois->format('Y');
        $this->mois = $this->lesMois[$this->mois->format('m')];
        // $this->data = Galerie::whereRaw("DAY(created_at) = DAY(NOW())")->get();
        // $pdf = Pdf::loadHTML(Blade::render('journalier', ['data' => $this->data, 'label' => 'PAIEMENT JOURNALIER DU MOIS DE '.$this->mois]));
        // Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
    }



}
