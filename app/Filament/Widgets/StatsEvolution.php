<?php

namespace App\Filament\Widgets;

use DateTime;
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

        


        return [
            //
            Stat::make('Loyer non payés', $data1.' Locataire(s)')
                ->description('Dernière mise à jour : '.$maj1)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger'),
            Stat::make('Loyer en cours', $data2.' Locataire(s)')
                ->description('Dernière mise à jour : '.$maj2)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),
            Stat::make('Loyer terminé', $data3.' Locataire(s)')
                ->description('Dernière mise à jour : '.$maj3)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
