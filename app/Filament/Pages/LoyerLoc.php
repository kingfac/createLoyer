<?php

namespace App\Filament\Pages;

use App\Models\Loyer;
use Filament\Pages\Page;
use Pages\EvolutionLoyer;

use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Filament\Resources\LoyerResource\Pages;
use Filament\Support\Enums\MaxWidth;


class LoyerLoc extends Page implements HasForms
{

    use InteractsWithForms;
    //protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = 'Loyer';

    protected static string $view = 'filament.pages.loyer-loc';
    protected static ?int $navigationSort = 6;

    public static function getNavigationBadge(): ?string
    {
        return Loyer::all()->count();
    }

    public static function getPages(): array
    {
        return [

            'evolution' => Pages\EvolutionLoyer::route('/{mois}/evolution'),
            //'evolution' => Pages\EvolutionLoyer::route('/{mois}/{annee}/evolution'),
        ];
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

}
