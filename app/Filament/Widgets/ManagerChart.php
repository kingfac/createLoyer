<?php

namespace App\Filament\Widgets;

use App\Models\Loyer;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class ManagerChart extends ChartWidget
{
    protected static ?string $heading = 'Loyer payé chaque mois';
    protected static ?int $sort = 2;    
    protected function getData(): array
    {
        $data = Trend::model(Loyer::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->sum('montant');
     
        return [
            'datasets' => [
                [
                    'label' => '',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
