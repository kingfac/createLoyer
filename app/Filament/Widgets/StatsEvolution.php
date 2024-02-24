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

        $this->calculDettes();
        
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
                ->description('Dernière mise à jour : '.$maj1)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger'),
            Stat::make('Loyers en cours', $data2.' Locataire(s)')
                ->description('Dernière mise à jour : '.$maj2)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),
            Stat::make('Payement Divers journalier', $data3.' $')
                ->description('Dernière mise à jour : '.$maj3)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Payement garantie journalier', $data4.' $')
                ->description('Dernière mise à jour : '.$maj3)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }



    public function calculDettes(){
        /*------------------------calcul des dettes------------------------------------*/
        $dettes_mois = [];
        $tot_g = [];
        $locataires = Locataire::where('actif', true)->get();
        foreach ($locataires as $locataire) {
            
            $id = $locataire->id;
    
            $locataire = Locataire::where('id', $id)->first();
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
          
            $rapport = [];
            // $mois_dette = [];
            
            // $m est le mois parcouru enregistré pour le calcul de somme 
            $total = 0;
            $m = 0; // mois encour de traitement
            $total_mois = 0;
            $nbrMois_paye = 0;
    
            /* total loyer */
            $loyers = Loyer::where('locataire_id', $id)->orderByRaw('created_at')->get();
            foreach ($loyers as $index => $loy)
            {
                    //convertir mois en nombre
                    $mloyer = intval($Mois2[$loy->mois]);
                    //dd( $mloyer, $loy->mois);
                    //si ce n'est pas le meme mois qu'on traite
                    if($m != $mloyer){
                        if($m != 0 ){
                            //s'il a une dette par rapport a ce mois
                            if ($total_mois < $locataire->occupation->montant) {
                                /* @endphp
                                <p>{{$loc->loyers[$loop->index-1]->mois}} : {{$total_mois}} / {{$loc->occupation->montant}}</p>
                                @php */
                                $total += $locataire->occupation->montant - $total_mois;
                                $rapport[] = [$locataire->loyers[$index-1]->mois ,$total_mois ,$locataire->occupation->montant, date("Y")-1];
                                $dettes_mois[] = $locataire->loyers[$index-1]->mois;
                            }
                        }
                        //chargement du mois suivant et calcul de la somme des loyers payess
                        $m = $mloyer;
                        $total_mois = 0;
                        $total_mois += $loy->montant;
                        $nbrMois_paye++;
                        
                        if(count($loyers) == 1){
                            $total += $locataire->occupation->montant - $total_mois;
                            $rapport[] = [$loy->mois ,$total_mois ,$locataire->occupation->montant, date("Y")-1];
                            $dettes_mois[] = $loy->mois;
                        }
                        //echo "<script>alert($loy->mois)</script>";
                    }
                    else{
                        $total_mois += $loy->montant;
                    }
            }
            // dd($this->dettes_mois);
            // return $total;
    
    
            $Nba = date("Y") - $locataire->ap; //nombre d'annee
            $mois_encours = date("m"); //mois encours
            $nbMois = ((13 * $Nba) - $locataire->mp) + date("m"); //nombre de mois total
            $x_encour = ($Nba == 0) ? $mois_encours :  (13 - $locataire->mp - $nbrMois_paye); // nombre de mois de l'annee precedente s'il y a 
        
        
    
        /* Affichage de mois d'arrieressss */
        if ($locataire->ap != null)
        {                                                       
                if ($x_encour >= 0){
                    if ($x_encour > 0){    
                        if ($Nba != 0){
                            for ($i = ($this->locataire->mp + $nbrMois_paye); $i <= 12; $i++){
                                $total += $locataire->occupation->montant;
                                $rapport[] = [$Mois1[$i > 9 ? $i : "0".$i] ,0 ,$locataire->occupation->montant, date("Y")-1];
                                $dettes_mois[] = $Mois1[$i > 9 ? $i : "0".$i];
                            }
                        }else{
                            /* Si tout se passe dans la meme annee */
                            for ($i = ($locataire->mp + $nbrMois_paye); $i <= $x_encour; $i++){
                                $total += $locataire->occupation->montant;
                                $rapport[] = [$Mois1[$i > 9 ? $i : "0".$i] ,0 ,$locataire->occupation->montant, date("Y")-1];
                                $dettes_mois[] = $Mois1[$i > 9 ? $i : "0".$i];
                            }
                        }
                    }
                    if ($Nba > 0){   
                        for ($i = 1; $i <= $mois_encours; $i++){
                            $total += $locataire->occupation->montant;
                            $rapport[] = [$Mois1[$i > 9 ? $i : "0".$i] ,0 ,$locataire->occupation->montant, date("Y")];
                            $dettes_mois[] = $Mois1[$i > 9 ? $i : "0".$i];
                        }
                    }
                }
        }
    
    
    
    
    
        array_push($tot_g, $total);
    
        // dd($tot_g);
            
            /*-----------------------fin calcul des dettes---------------------------------*/
    }
        }


}
