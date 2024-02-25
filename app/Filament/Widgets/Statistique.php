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


       
        $prevu = Locataire::all()->where('actif', true)->sum('occupation.montant');
        $recu = Loyer::whereRaw(" (mois) = '$mois_t'  and YEAR(created_at) = '$ann' ")->sum('montant');
        // $recu = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id')
        // ->selectRaw('locataires.*, loyers.created_at as dl')
        // ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
        // ->orderByRaw('locataires.id, loyers.created_at desc')
        // ->get();
        // $somme = 0;
        // $_id = 0;
        // foreach ($recu as $val) {
        //     # code...
        //     if($_id != $val->id){
        //         $somme += $val->somme;
        //         $_id = $val->id;
        //     }
        // }
        
        // $recu = $somme;

        $revenu = $recu - $data1;
        
        return [
            Stat::make('Prevision finale de '.$this->mois. ' '. $this->annee,$prevu.' $')
                ->description('Loyer prevu  ce mois de '.$this->mois)
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
            /* Stat::make('Dette Mensuelle', $montPrevuI - $montPayeI)
                ->description('Les dettes de ce mois')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]), */
            
        ];
    }
}
