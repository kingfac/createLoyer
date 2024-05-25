<?php

namespace App\Filament\Widgets;

use DateTime;
use App\Models\Loyer;
use App\Models\Divers;
use App\Models\Garantie;
use App\Models\Locataire;
use Illuminate\Support\Facades\Date;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use PHPUnit\Event\TestSuite\Loaded;

class StatsEvolution extends BaseWidget
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
        $this->mois = new DateTime();
        $this->annee = $this->mois->format('Y');
        $this->mois = $this->lesMois[$this->mois->format('m')];
        
        //dd($this->mois, $this->annee);


        $data1 = 0;
        $data2 = 0;
        $data3 = 0;
        $_id = 0;
        $maj1 = "";
        $maj2 = '';
        $maj3 = '';

        $dettes = $this->calculDettes();
        
        //Locataires non payeee
        foreach (Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id', 'left outer')
        ->selectRaw('locataires.*, loyers.created_at as dl')
        ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
        ->orderByRaw('locataires.id, loyers.created_at desc')
        ->get() as $val) {
            # code...
            if ($_id != $val->id && $val->somme == null){
                $_id = $val->id;
                $data1 += 1;
                $maj1 = $val->dl;
            }
        }

        //Locataire payee partielle
        $_id = 0;
        foreach (Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id')
        ->selectRaw('locataires.*, loyers.created_at as dl')
        ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
        ->orderByRaw('locataires.id, loyers.created_at desc')
        ->get() as $val) {
            # code...
            if ($_id != $val->id && $val->somme < $val->occupation->montant && $val->somme > 0){
                $_id = $val->id;
                $data2 += 1;
                $maj2 = $val->dl;
            }
        }

        //Locataire terminee
        $_id = 0;
        foreach (Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id')
        ->where('actif', true)
        ->selectRaw('locataires.*, loyers.created_at as dl')
        ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
        ->orderByRaw('locataires.id, loyers.created_at desc')
        ->get() as $val) {
            # code...
            if ($_id != $val->id && $val->somme == $val->occupation->montant){
                $_id = $val->id;
                $data3 += 1;
                $maj3 = $val->dl;
            }
        }
        $d = NOW()->format('Y-m-d');
        
        $data3 = Divers::whereRaw(" date(created_at) = '$d' ")->sum('total');
     
        $data4 = Garantie::whereRaw(" date(created_at) = '$d' ")->sum('montant');
        


        return [
            //
            Stat::make('Loyers non payés', $data1.' Locataire(s)')
                // ->description('Dernière mise à jour : '.$maj1)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger'),
            Stat::make('Total dettes antérieures', $dettes.'$')
                // ->description('Derni'.$maj2)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),
            Stat::make('Payement Divers journalier', $data3.' $')
                // ->description('Dernière mise à jour : '.$maj3)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Payement garantie journalier', $data4.' $')
                // ->description('Dernière mise à jour : '.$maj3)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }



    public function calculDettes(){
        /*------------------------calcul des dettes------------------------------------*/
     
        $annee_en_cours = intval(NOW()->format('Y'));
        $mois_dettes = [];
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
        $locataires = Locataire::where('actif', true)->orderBy('id','DESC')->get();

        //on calcul des dettes pour chaque locataire
        foreach ($locataires as $locataire) {
            
            //on recupere le mp et ap
            $mp_int =intval( $locataire->mp);
            $mp_trans = '';
            $ap_int = $locataire->ap;
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


                    $loyer = Loyer::where('locataire_id', $locataire->id)->whereRaw(" (mois) = '$mois_n' and (annee) = '$annee_en_cours'  ")->get();
                    $loyer_montant = $loyer->sum('montant');

                    
                    if($loyer_montant < $locataire->occupation->montant )
                    {
                        
                        array_push($montant_dette, $locataire->occupation->montant-$loyer_montant);
                        array_push($mois_dettes, $mois_n);
                        
                        
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
    
    
                        $loyer = Loyer::where('locataire_id', $locataire->id)->whereRaw(" (mois) = '$mois_n' and (annee) = '$ap_int'  ")->get();
                        $loyer_montant = $loyer->sum('montant');
    
                        
                        if($loyer_montant < $locataire->occupation->montant )
                        {
                            
                            array_push($montant_dette, $locataire->occupation->montant-$loyer_montant);
                            array_push($mois_dettes, $mois_n, $ap_int);
                            
                            
                        }
                        
                         if($mois == 12){
                             $mp_com = 1;
                         }
                        
                    }

                }

               
                
            }            
            
            
            
            
        }
        // dd($mois_dettes, $montant_dette, array_sum($montant_dette));
        return array_sum($montant_dette);
    }


}
