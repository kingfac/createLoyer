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
use Carbon\Carbon;
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
    public $anciennes_garanties;
    public $galeries;
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
        $pdf = Pdf::loadHTML(Blade::render('rapport_mensuel', [
            'galeries' =>  $this->galeries,
            'label' => 'Rapport mensuel de '.$this->mois. " ".$this->annee,
            'mois' => $this->mois,
            'annee' => $this->annee
            
        ]))->setPaper('a3', 'landscape');
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
        return view('livewire.rapport-mensuel');
    }

    /* fonction qui renvoie les anciennes garanties */
    public function getAnciennesGaranties($record)
    {
        $galerie = Galerie::where('id', $record->id)->first();
        $occups = $galerie->occupations;
        $locs=[];
        $somme=[];
        $sommeA=[];
        $sommeL=[];
        $sommeLA=[];
        $mois = intval($this->Mois2[$this->mois]);

        foreach($occups as $occup){
            foreach ($occup->locataires as $locataire) {
                # code...
                if($locataire->actif){
                    array_push($locs, $locataire);
                }
            }
        }
        foreach ($locs as $loc) {
            $loyersGarantie = Loyer::where('locataire_id', $loc->id)->where('garantie',true)->whereMonth('created_at','=', $mois)->whereYear('created_at', $this->annee)->get();
            $loyersGarantieA = Loyer::where('locataire_id', $loc->id)->where('garantie',true)->get();

            $garanties = Garantie::where('locataire_id',$loc->id)->where('restitution',false)->whereMonth('created_at','=', $mois)->whereYear('created_at', $this->annee)->get();
            $garantiesA = Garantie::where('locataire_id',$loc->id)->where('restitution',false)->get();
            array_push($somme,$garanties->sum('montant'));
            array_push($sommeA,$garantiesA->sum('montant'));
            array_push($sommeL,$loyersGarantie->sum('montant'));
            array_push($sommeLA,$loyersGarantieA->sum('montant'));
        }
        //calcul des anciennes garanties
                       
        return (array_sum($sommeA)-array_sum($sommeLA))-(array_sum($somme)-array_sum($somme));
    }

    /* fonction qui renvoie les nouvelles garanties */
    public function getNouvellesGaranties($record)
    {
        $galerie = Galerie::where('id', $record->id)->first();
        $occups = $galerie->occupations;
        $locs=[];
        $somme=[];
        $mois = intval($this->Mois2[$this->mois]);

        foreach($occups as $occup){
            foreach ($occup->locataires as $locataire) {
                # code...
                if($locataire->actif){
                    array_push($locs, $locataire);
                }
            }
        }
        foreach ($locs as $loc) {
            $garanties = Garantie::where('locataire_id',$loc->id)->whereMonth('created_at','=', $mois)->whereYear('created_at', $this->annee)->get();
            array_push($somme,$garanties->sum('montant'));
        }
        //calcul des anciennes garanties
                       
        return array_sum($somme);
    }

    /* fonction pour calculer les dettes anterieures percues*/
    public function getDettesAnterieuresPercues($record)
    {
        
        /////////////////////////////////////
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
                if($lo->actif){

                    // ici je n arrive pas a obtenir les loyers dont le mois est < au mois actuel
                    $loyers = Loyer::where('locataire_id',$lo->id)->whereRaw(" (mois) != '$mois' and  YEAR(created_at) = $this->annee and MONTH(created_at) = '$mois'  and YEAR(created_at) =  '$this->annee' ")->get();
                    // dd($loyers);
                    // dd($loyers->count(), $lo->noms);
                    if($loyers->count() >= 1){
    
                        foreach ($loyers as $loyer) {
                            // dd(($loyer->mois), $lo->noms,$loyer->id);
                            # code...
                            if(intval($this->Mois2[$loyer->mois]) <  intval($this->Mois2[$this->mois])){
                                array_push($somme,$loyer->montant);  
                            }
                        }
                    }
    
                    // array_push($somme,$loyers->sum("montant"));  
                }           

                
            }
        }

    
        return array_sum($somme);
    }

    public function MontantMois($record){
        $galerie = Galerie::where('id', $record->id)->first();
        $mois = $this->mois;
        $occups = $galerie->occupations;
        $locs=[];
        foreach($occups as $occup){
            array_push($locs, $occup->locataires);
        }
        $somme=[];
        // $mois_suivant =  $this->lesMois['0'.$mois +1];
     

        foreach ($locs as $loc) {
            foreach ($loc as $lo) {                
                $loyers = Loyer::where('locataire_id',$lo->id)->whereRaw(" (mois) = '$mois' ")->sum('montant');
                array_push($somme,$loyers);
            }
        }

        return array_sum($somme);
    }

    public function MontantSuivantMois($record){
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
        $mois_suivant = $this->lesMois[$mois_suivant];

        foreach ($locs as $loc) {
            foreach ($loc as $lo) {                
                $loyers = Loyer::where('locataire_id',$lo->id)->whereRaw(" (mois) = '$mois_suivant' ")->sum('montant');
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
                TextColumn::make('galerie')->label('Galerie')
                    ->default(function(Galerie $galerie){
                        return $galerie->nom."-".$galerie->num;
                    }),
                TextColumn::make('Anciennes garanties')
                    ->default(function(Galerie $record){
                        $this->anciennes_garanties = $this->getAnciennesGaranties($record);
                       return $this->anciennes_garanties ;
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

                        return $this->MontantMois($record);
                    })   
                    ->label(function(){
                        $mois = intval($this->Mois2[$this->mois]);
                        return $this->lesMois['0'.$mois];
                    })                  
                    ->suffix(' $'),
                TextColumn::make('Montant perçu au mois suivant')
                    ->default(function(Galerie $record){
                            return $this->MontantSuivantMois($record);
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
                        $sommeCemois=[];

                        foreach ($locs as $loc) {
                            foreach ($loc as $lo) {                
                                
                                // $loyer = Loyer::where(['locataire_id'=> $lo->id, 'mois' => $mois, 'annee'=>$annee])->sum('montant');
                                // dd($loyer);
                                // array_push($loyers_locs, $loyer);
                                $loyers = Loyer::where('locataire_id',$lo->id)->whereRaw("mois = '$this->mois' and annee = $this->annee   ")->sum('montant');
                                array_push($sommeCemois,$loyers);
                               
                            }
                        }

                        $galerie = Galerie::where('id', $record->id)->first();
                        $mois = intval($this->Mois2[$this->mois]);

                        $occups = $galerie->occupations;
                        $locs=[];
                        foreach($occups as $occup){
                            array_push($locs, $occup->locataires);
                        }
                        $sommeMoisSuivant=[];
                        // $mois_suivant =  $this->lesMois['0'.$mois +1];
                        $mois_suivant = '0'.$mois +1;
                        $mois_suivant = $this->lesMois[$mois_suivant];

                        foreach ($locs as $loc) {
                            foreach ($loc as $lo) {                
                                $loyers = Loyer::where('locataire_id',$lo->id)->whereRaw(" (mois) = '$mois_suivant' ")->sum('montant');
                                array_push($sommeMoisSuivant,$loyers);
                            }
                        }




                    $total_percu = $this->getNouvellesGaranties($record)+$this->getDettesAnterieuresPercues($record)+array_sum($sommeCemois)+array_sum($sommeMoisSuivant);
                    return $total_percu;

                
                })
                ->suffix(' $'),
                TextColumn::make('Montant attendu')
                    ->default(function(Galerie $record){
                        return $this->getSomme($record);
                    })
                    ->suffix(' $'),
                
                TextColumn::make('Montant non perçu')
                    ->default(function (Galerie $record){
                        return $this->getSomme($record)-$this->MontantMois($record);
                        // return $this->getLoyerGalerie(Galerie::where('id',$record->id)->first(), $this->mois,$this->annee) - $this->getSomme(Galerie::where('id',$record->id)->first());
                    })
                    ->suffix(' $'),

                TextColumn::make('Taux de réalisation')
                    ->default(function(Galerie $record){
                        if($this->getSomme($record) != 0){
                            $result = round((($this->MontantMois($record))/$this->getSomme($record))*100,2);
                        }
                        else{
                            $result = 0;
                        }
                        return $result;
                        
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

    public function mount(){
        $this->galeries = Galerie::all();

    }



}
