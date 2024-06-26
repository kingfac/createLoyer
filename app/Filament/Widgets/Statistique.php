<?php

namespace App\Filament\Widgets;

use App\Models\Depense;
use DateTime;
use App\Models\Loyer;
use App\Models\Divers;
use App\Models\Locataire;
use App\Models\Occupation;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\Date;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class Statistique extends BaseWidget
{
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
        '12' => 'Décembre'];
    
    protected function getStats(): array
    {
        $mois = intval(NOW()->format('m'));
        $mois_t='';
        if($mois <= 9){
            $mois_t =$this->lesMois['0'.$mois];
        }elseif($mois >= 10){
            $mois_t =$this->lesMois[$mois];
        }
        $ann = intval(NOW()->format('Y'));
        $this->mois = new DateTime();
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y');
        $this->annee = $this->mois->format('Y');
        $this->mois = $this->lesMois[$this->mois->format('m')];
        // nombre de locataire ayant de dettes        
        $mois = intval(NOW()->format('m'));

        //payement par jour
        $data1 = Depense::whereRaw(" MONTH(created_at) = '$mois' and YEAR(created_at) = '$ann' ")->get()->sum('total');
        // prevision mensuelle
        $montPrevu = Trend::query(Occupation::where('actif',true))
            ->between(
                start : now()->startOfMonth(),
                end : now()->endOfMonth()
            )
            ->perMonth()
            ->sum('montant');
        // dette par mois
        $montPaye = Trend::model(Loyer::class)
            ->between(
                start : now()->startOfMonth(),
                end : now()->endOfMonth()
            )
            ->perMonth()
            ->sum('montant');
        $montPrevuI = array_sum($montPrevu->map(fn (TrendValue $value) => $value->aggregate)->toArray());
        $montPayeI = array_sum($montPaye->map(fn (TrendValue $value) => $value->aggregate)->toArray());


        //Locataires non payeee
        $_id = 0;
        $ctrdette = 0;
        foreach (Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id', 'left outer')
        ->selectRaw('locataires.*, loyers.created_at as dl')
        ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
        ->orderByRaw('locataires.id, loyers.created_at desc')
        ->get() as $val) {
            # code...
            if (($_id != $val->id && $val->somme == null) || $_id != $val->id && $val->somme < $val->occupation->montant && $val->somme > 0){
                $_id = $val->id;
                $ctrdette += 1;
            }
        }


        $this->calculDettesV();
       
        $prevu = Locataire::all()->where('actif', true)->sum('occupation.montant');
        $recu = Loyer::whereRaw(" (mois) = '$mois_t'  and YEAR(created_at) = '$ann' ")->sum('montant');

        $revenu = $recu - $data1;
        
        return [
            Stat::make('Prevision finale de '.$this->mois. ' '. $this->annee,$prevu+$this->calculDettesV().' $')
                ->description('Loyer prevu de '.$this->mois.' + dettes antérieures')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger'),

            Stat::make('Loyer perçu de '.$this->mois. ' '.$this->annee, $recu.' $')
                ->description("Loyer perçu de ".$this->mois.'-'.$this->annee)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning'),

            Stat::make('Depenses de '.$this->mois. ' '.$this->annee, $data1.' $')
                ->description("Depenses de ".$this->mois.'-'.$this->annee)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),


            Stat::make('Revenu du mois de '.$this->mois, $revenu.' $')
                ->description("Revenu de ".$this->mois.'-'.$this->annee. ' (prévision finale)-(dépenses)' )
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger'),
            
        ];
    }


    public function calculDettesV(){
        /*------------------------calcul des dettes------------------------------------*/
     
        $annee_en_cours = intval(NOW()->format('Y'));
        $mois_dettes = [];
        $annee_dettes = [];
        $montant_dette = [];
        
        
        // $locataire = Locataire::where('id', $id)->first();
        $Mois1 = [
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
        $Mois2 = [
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
        
        $mois_en_cours = intval(NOW()->format('m'));

        //on recupere tous les locataires actifs
        // $id = $this->locataire_id;
        $locats = Locataire::where('actif', true)->orderBy('id','DESC')->get();


        foreach ($locats as $locat) {
            
            //$locataires = Locataire::where(['id' => $id,'actif' => true])->orderBy('id','DESC')->get();
            //on calcul des dettes pour chaque locataire
            $ap = $locat->value('ap');
            $mp =  $locat->value('mp');
    
            //on verifie d abord que le ap et mp existent
            if($locat->value('ap') != null && $locat->value('mp') != null){
    
                    
                    //on recupere le mp et ap
                    $mp_int =intval( $locat->mp);
                    $mp_trans = '';
                    $ap_int = $locat->ap;
                    //-------------------------------
        
                    //on transforme mp 02 => fevrier
                    if($mp_int <= 9){
                        $mp_trans = $Mois1['0'.$mp_int];
                    }
                    elseif($mp_int >= 10){
                        $mp_trans = $Mois1[$mp_int];
                    }
                    //--------------------------------
                
                    //on va parcourrir tous les mois a partir mp et ap jusque au mois en cours
        
                    if($ap_int == $annee_en_cours){
                        
                        for ($mois=$mp_int; $mois <= $mois_en_cours ; $mois++) { 
        
                            $mois_n = '';
                            //on transforme mp 02 => fevrier
                            if($mois < 9){
                                $mois_n = $Mois1['0'.$mois];
                            }
                            elseif($mois >= 10){
                                $mois_n = $Mois1[$mois];
                            }
                            //--------------------------------
        
        
                            $loyer = Loyer::where('locataire_id', $locat->id)->whereRaw(" (mois) = '$mois_n' and (annee) = '$annee_en_cours'  ")->get();
                            $loyer_montant = $loyer->sum('montant') ?? 0;
        
                            
                            if($loyer_montant < $locat->occupation->montant )
                            {
                                
                                array_push($montant_dette, ($locat->occupation->montant - $loyer_montant));
                                array_push($mois_dettes, $mois_n);
                                array_push($annee_dettes, $ap_int);
        
                                
                                
                            }
                            
                           
                        }
                        
                        
                    }
        
                    if($ap_int < $annee_en_cours){
                        $mois_fin = 12;
                        $mp_com = $mp_int;
        
                        for ($ap_int; $ap_int <= $annee_en_cours  ; $ap_int ++) { 
        
                            
                            if($ap_int == $annee_en_cours)
                            {
                                $mois_fin = $mois_en_cours;
        
                            }
                            for ($mois=$mp_com; $mois <= $mois_fin ; $mois++) { 
            
                                $mois_n = '';
                                //on transforme mp 02 => fevrier
                                if($mois <= 9){
                                    $mois_n = $Mois1['0'.$mois];
                                }
                                elseif($mois >= 10){
                                    $mois_n = $Mois1[$mois];
                                }
                                //--------------------------------
            
            
                                $loyer = Loyer::where('locataire_id', $locat->id)->whereRaw(" (mois) = '$mois_n' and (annee) = '$ap_int'  ")->get();
                                $loyer_montant = $loyer->sum('montant');
            
                                
                                if($loyer_montant < $locat->occupation->montant )
                                {
                                    // dd($locataire->occupation->montant,$loyer_montant);
                                    
                                    array_push($montant_dette, ($locat->occupation->montant - $loyer_montant));
                                    array_push($mois_dettes, $mois_n);
                                    array_push($annee_dettes, $ap_int);
                                    
                                    
                                }
                                
                                 if($mois == 12){
                                     $mp_com = 1;
                                 }
                                
                            }
        
        
                       
                        
                    }            
                    
                    
                    
                    
                }
        
                ///on affecte les dettes 
                // $dettes_mois = $mois_dettes;
                // $dettes_annees = $annee_dettes;
                // $dettes_montant = $montant_dette;
                // dd($mois_dettes, $montant_dette, array_sum($montant_dette), $annee_dettes);
                // return array_sum($montant_dette);
    
    
            }
            
            
            
        }
        
        // dd($mois_dettes,$annee_dettes,$montant_dette);
        return (array_sum($montant_dette));
    }

}
