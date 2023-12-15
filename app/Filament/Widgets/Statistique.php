<?php

namespace App\Filament\Widgets;

use App\Models\Loyer;
use App\Models\Occupation;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class Statistique extends BaseWidget
{
    protected function getStats(): array
    {
        //payement par jour
        $data1 = Trend::model(Loyer::class)
            ->between(
                start: now()->startOfDay(),
                end: now()->endOfDay(),
            )
            ->perDay()
            ->sum('montant');
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

        
        return [
            Stat::make('Payement Journalier', array_sum($data1->map(fn (TrendValue $value) => $value->aggregate)->toArray()))
                ->description("Les loyers payés aujourd'hui")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Prevision Mensuelle', array_sum($montPrevu->map(fn (TrendValue $value) => $value->aggregate)->toArray()))
                ->description('Les loyers à payés ce mois')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Dette Mensuelle', $montPrevuI - $montPayeI)
                ->description('Les dettes de ce mois')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
