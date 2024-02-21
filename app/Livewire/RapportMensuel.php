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
    public function getSomme($gal):int
    {

            $mois = intval($this->Mois2[$this->mois]);

            $occups = $gal->occupations;
            $somme_occu = $occups->sum('montant');

        
            $locs=[];
            $somme_locs=[];
            foreach($occups as $occup){
                array_push($locs, $occup->locataires->where('actif',true));
            }

            foreach ($locs as $loc) {
                foreach ($loc as $lo) {                
                    array_push($somme_locs, $lo->occupation->montant);
                }
            }

            $loyers_locs = array_sum($somme_locs);
            
            
            
        return $loyers_locs;
    }

    public function getLoyerGalerie ($gal,$mois,$annee):int
    {
        

        $occups = $gal->occupations;

    
        $locs=[];
        $loyers_locs=[];
        foreach($occups as $occup){
            array_push($locs, $occup->locataires->where('actif',true));
        }

        foreach ($locs as $loc) {
            foreach ($loc as $lo) {                
                
                $loyer = Loyer::where(['locataire_id'=> $lo->id, 'mois' => $mois, 'annee'=>$annee])->sum('montant');
                // dd($loyer);
                array_push($loyers_locs, $loyer);
            }
        }

        $loyers_locss = array_sum($loyers_locs);
        // dd($this->getSomme($gal),$loyers_locss);
        
        return $loyers_locss;
        
    }


    public function getSortieDette($gal,$mois,$annee){
        $occups = $gal->occupations;
            $somme_occu = $occups->sum('montant');

        
            $locs=[];
            $somme_sortie_dette=[];
            $moiss = intval($this->Mois2[$mois]);

            foreach($occups as $occup){
                array_push($locs, $occup->locataires->where('actif',false));
            }

            foreach ($locs as $loc) {
                foreach ($loc as $lo) {           
                    $sm = Garantie::where(['locataire_id' , $lo->id, 'restitution' == true])->whereRaw(["MONTH(created_at) == $moiss "]); 
                    // dd($sm != null);
                    if($sm!= null){

                        array_push($somme_sortie_dette, 1);
                    }
                }
            }

            $nbr = array_sum($somme_sortie_dette);
            return $nbr;
    }


    public function getTauxRealisation($gal,$mois, $annee):int
    {
        $montant_paye = $this->getSomme($gal,$mois,$annee) - $this->getLoyerGalerie($gal,$mois,$annee);
        $montant_attendu = $this->getSomme($gal);

        if($montant_attendu == 0){
            return 0;
        }
        $taux = ($montant_paye/$montant_attendu)*100;
        return $taux;
    }
    public function getTotalDettes():int
    {
        return 0;
    }

    protected $listeners = ['m11a' => '$refresh'];

    #[On('m11')] 
    public function update($mois, $annee)
    {
        $this->annee = $annee;
        $this->mois = $mois;
        $this->dispatch('m11a');        
    }
    
    public function render()
    {
        return view('livewire.rapport-mensuel');
    }

    /* fonction qui renvoie les anciennes garanties */
    public function getAnciennesGaranties($record)
    {
        $galerie = Galerie::where('id', $record->id)->first();
        $occups = $galerie->occupations;
        $locs=[];
        $somme=[];
        $mois = intval($this->Mois2[$this->mois]);

        foreach($occups as $occup){
            array_push($locs, $occup->locataires);
        }

        foreach ($locs as $loc) {
            foreach ($loc as $lo) {                
                $garanties = Garantie::where('locataire_id',$lo->id)
                    ->whereRaw("MONTH(created_at) < $mois and restitution=false and YEAR(created_at) <= $this->annee");
                array_push($somme,$garanties->sum('montant'));
            }
        }
                       
        return array_sum($somme);
    }

    /* fonction qui renvoie les nouvelles garanties */
    public function getNouvellesGaranties($record)
    {
        $mois = intval($this->Mois2[$this->mois]);
        $galerie = Galerie::where('id', $record->id)->first();
        $occups = $galerie->occupations;
        $locs=[];
        $somme=[];

        foreach($occups as $occup){
            array_push($locs, $occup->locataires);
        }

        foreach ($locs as $loc) {
            foreach ($loc as $lo) {                
                $garanties = Garantie::where('locataire_id',$lo->id)
                    ->whereRaw("MONTH(created_at) = $mois and restitution=0 and YEAR(created_at) = $this->annee")
                    ->sum('montant');

                array_push($somme,$garanties);
            }
        }

        return array_sum($somme);
    }

    /* fonction pour calculer les dettes anterieures percues*/
    public function getDettesAnterieuresPercues($record)
    {
        $galerie = Galerie::where('id', $record->id)->first();
        $mois = intval($this->Mois2[$this->mois]);
        $somme=[];
        $occups = $galerie->occupations;
        $locs=[];

        foreach($occups as $occup){
            array_push($locs, $occup->locataires);
        }
        foreach ($locs as $loc) {
            foreach ($loc as $lo) {                
                // ici je n arrive pas a obtenir les loyers dont le mois est < au mois actuel
                $loyers = Loyer::where('locataire_id',$lo->id)->whereRaw(" (mois) != '$this->mois' and YEAR(created_at) = $this->annee ")->sum('montant');
                array_push($somme,$loyers);  
            }
        }

    
        return array_sum($somme);
    }

    public function table(Table $table): Table
    {    
        return $table
            ->query(
                Galerie::query()    
            )
            ->columns([
                TextColumn::make('nom')->label('Galerie'),
                TextColumn::make('Anciennes garanties')
                    ->default(function(Galerie $record){
                       return $this->getAnciennesGaranties($record);
                    })                     
                    ->suffix(' $'),
                TextColumn::make('Nouvelles garanties')
                    ->default(function(Galerie $record){
                        return $this->getNouvellesGaranties($record);
                    })                    
                    ->suffix(' $'),

                TextColumn::make('Dettes antérieures perçues')
                    ->default(function(Galerie $record){
                        return $this->getDettesAnterieuresPercues($record);        
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

                        foreach ($locs as $loc) {
                            foreach ($loc as $lo) {                
                                
                                // $loyer = Loyer::where(['locataire_id'=> $lo->id, 'mois' => $mois, 'annee'=>$annee])->sum('montant');
                                // dd($loyer);
                                // array_push($loyers_locs, $loyer);
                                $loyers = Loyer::where('locataire_id',$lo->id)->whereRaw("mois = '$this->mois' and annee = $this->annee   ")->sum('montant');
                                array_push($somme,$loyers);
                               
                            }
                        }

                        return array_sum($somme);

                    })   
                    ->label(function(){
                        $mois = intval($this->Mois2[$this->mois]);
                        return $this->lesMois['0'.$mois];
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
                            $somme=[];
                            // $mois_suivant =  $this->lesMois['0'.$mois +1];
                            $mois_suivant = '0'.$mois +1;

                            foreach ($locs as $loc) {
                                foreach ($loc as $lo) {                
                                    $loyers = Loyer::where('locataire_id',$lo->id)->whereRaw(" MONTH(created_at) = $mois_suivant")->sum('montant');
                                    array_push($somme,$loyers);
                                }
                            }

                            for($i=0; $i < count($locs); $i++){
                                //ne doit pas recupere paiement anticipatif
                            }
                            return array_sum($somme);
                        })
                        ->label(function(){
                            $mois = intval($this->Mois2[$this->mois]);
                            $mois_suivant = '0'.$mois +1;
                            return $this->lesMois[$mois_suivant];
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
                    $somme=[];
                    $somme1=[];
                    $somme2=[];
                    $somme3=[];
                    $somme4=[];
                    // $mois_suivant =  $this->lesMois['0'.$mois +1];
                    $mois_suivant = '0'.$mois +1;

                    foreach ($locs as $loc) {
                        foreach ($loc as $lo) {                
                            
                            // $loyer = Loyer::where(['locataire_id'=> $lo->id, 'mois' => $mois, 'annee'=>$annee])->sum('montant');
                            // dd($loyer);
                            // array_push($loyers_locs, $loyer);
                            $loyers1 = Loyer::where('locataire_id',$lo->id)->whereRaw(" MONTH(created_at) = $mois_suivant")->sum('montant');
                            array_push($somme1,$loyers1);
                            $loyers2 = Loyer::where('locataire_id',$lo->id)->whereRaw("mois = '$this->mois' and annee = $this->annee   ")->sum('montant');
                            array_push($somme2,$loyers2);
                            $loyers3 = Loyer::where('locataire_id',$lo->id)->whereRaw(" mois != '$this->mois' and YEAR(created_at) = $this->annee ")->sum('montant');
                            array_push($somme3,$loyers3);
                            $garanties = Garantie::where('locataire_id',$lo->id)->whereRaw("MONTH(created_at) = $mois and restitution=0 and YEAR(created_at) = $this->annee")->sum('montant');
                            array_push($somme4,$garanties);
                        }
                    }


                    // for($i=0; $i < count($locs); $i++){
                    //     //ne doit pas recupere paiement anticipatif
                    // }
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
                        return $this->getLoyerGalerie(Galerie::where('id',$record->id)->first(), $this->mois,$this->annee) - $this->getSomme(Galerie::where('id',$record->id)->first());
                    })
                    ->suffix(' $'),

                TextColumn::make('Taux de réalisation')
                    ->default(function(Galerie $record){
                        return $this->getTauxRealisation(Galerie::where('id',$record->id)->first(),$this->mois, $this->annee);
                    })
                    ->suffix(' %'),
                TextColumn::make('Sorties avec dettes')
                    ->default(function(Model $record){
                        return $this->getSortieDette($record,$this->mois, $this->annee);
                    }),
               /*  TextColumn::make('Total dettes')
                    ->default(function(Galerie $record){
                        return $this->getTotalDettes();
                    }) */
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
        $this->annee = $this->mois->format('Y');
        $this->mois = $this->lesMois[$this->mois->format('m')];
    }



}
