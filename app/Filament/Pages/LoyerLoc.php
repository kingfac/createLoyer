<?php

namespace App\Filament\Pages;

use App\Models\Loyer;
use Filament\Pages\Page;
use Pages\EvolutionLoyer;

use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Filament\Resources\LoyerResource\Pages;


class LoyerLoc extends Page implements HasForms
{

    use InteractsWithForms;
    //protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = 'Loyer';

    protected static string $view = 'filament.pages.loyer-loc';

    public static function getNavigationBadge(): ?string
    {
        return Loyer::all()->count();   
    }

    public static function getPages(): array
    {
        return [
           
            'evolution' => Pages\EvolutionLoyer::route('/{mois}/evolution'),
        ];
    }

}
