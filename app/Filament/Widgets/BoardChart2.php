<?php

namespace App\Filament\Widgets;

use App\Models\Loyer;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class BoardChart2 extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'boardChart2';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Statistiques des loyers perçus cette année';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $annee = intval(NOW()->format('Y'));

        $paie_janv = Loyer::whereRaw(" mois = 'Janvier' and annee = '$annee'")->sum('montant');
        $paie_fev = Loyer::whereRaw(" mois = 'Février' and annee = '$annee'")->sum('montant');
        $paie_mars = Loyer::whereRaw(" mois = 'Mars' and annee = '$annee'")->sum('montant');
        $paie_avril = Loyer::whereRaw(" mois = 'Avril' and annee = '$annee'")->sum('montant');
        $paie_mais = Loyer::whereRaw(" mois = 'Mais' and annee = '$annee'")->sum('montant');
        $paie_juin = Loyer::whereRaw(" mois = 'Juin' and annee = '$annee'")->sum('montant');
        $paie_juillet = Loyer::whereRaw(" mois = 'Juillet' and annee = '$annee'")->sum('montant');
        $paie_aout = Loyer::whereRaw(" mois = 'Aout' and annee = '$annee'")->sum('montant');
        $paie_sept = Loyer::whereRaw(" mois = 'Septembre' and annee = '$annee'")->sum('montant');
        $paie_oct = Loyer::whereRaw(" mois = 'Octobre' and annee = '$annee'")->sum('montant');
        $paie_nov = Loyer::whereRaw(" mois = 'Novembre' and annee = '$annee'")->sum('montant');
        $paie_dec = Loyer::whereRaw(" mois = 'Décembre' and annee = '$annee'")->sum('montant');
        
        return [
            'chart' => [
                'type' => 'area',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'BoardChart2',
                    'data' => [$paie_janv, $paie_fev, $paie_mars, $paie_avril,  $paie_mais,  $paie_juin, $paie_juillet, $paie_aout, $paie_sept, $paie_oct, $paie_nov, $paie_dec],
                ],
            ],
            'xaxis' => [
                'categories' => ['Jan', 'Fév', 'Mars', 'Avril', 'Mais', 'Juin', 'Juillet', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'],
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#f59e0b'],
            'stroke' => [
                'curve' => 'smooth',
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
        ];
    }
}
