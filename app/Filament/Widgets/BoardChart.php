<?php

namespace App\Filament\Widgets;

use App\Models\Depense;
use App\Models\Divers;
use App\Models\Loyer;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class BoardChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'boardChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Statistiques divers, dépenses et loyers perçus cette année';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $d = NOW()->format('Y');
        
        $data3 = Divers::whereRaw(" YEAR(created_at) = '$d' ")->sum('total');
        $depenses = Depense::whereRaw(" YEAR(created_at) = '$d' ")->sum('total');
        $loyers = Loyer::whereRaw(" YEAR(created_at) = '$d' ")->sum('montant');

             return [
            'chart' => [
                'type' => 'donut',
                'height' => 300,
            ],
            'series' => [$data3, $depenses, $loyers],
            'labels' => ['Divers', 'Dépenses', 'Loyers perçus'],
            'legend' => [
                'labels' => [
                    'fontFamily' => 'inherit',
                ],
            ],
        ];
    }
}
