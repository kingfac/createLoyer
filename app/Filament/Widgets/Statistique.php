<?php

namespace App\Filament\Widgets;

use DateTime;
use App\Models\Loyer;
use App\Models\Locataire;
use App\Models\Occupation;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Facades\Date;

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
        $this->mois = new DateTime();
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y');
        $this->annee = $this->mois->format('Y');
        $this->mois = $this->lesMois[$this->mois->format('m')];
        // nombre de locataire ayant de dettes        

        //payement par jour
        $data1 = Loyer::whereRaw("DAY(created_at) = DAY(NOW())")->get()->sum('montant');
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



        $prevu = Locataire::all()->sum('occupation.montant');
        $recu = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id')
        ->selectRaw('locataires.*, loyers.created_at as dl')
        ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
        ->orderByRaw('locataires.id, loyers.created_at desc')
        ->get();
        $somme = 0;
        $_id = 0;
        foreach ($recu as $val) {
            # code...
            if($_id != $val->id){
                $somme += $val->somme;
                $_id = $val->id;
            }
        }
        
        $recu = $somme;
        
        return [
            Stat::make('Nombre des locataires ayant des dettes', $ctrdette.'')
                ->description("Conerne le mois de ".$this->mois.'-'.$this->annee)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning'),

            Stat::make('Payement journalier', $data1.' $')
                ->description("Les loyers payés aujourd'hui : ".$lelo)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Prevision mensuelle', $recu.'$ sur '.$prevu.'$')
                ->description('Loyers perçu / prevu, pour ce mois : '.$this->mois)
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
