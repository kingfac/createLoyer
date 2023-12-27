<?php

namespace App\Filament\Resources\LoyerResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use App\Filament\Resources\LoyerResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;

class Loca extends ListRecords implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = LoyerResource::class;
    public $mois;
    public $annee;

    protected function getHeaderActions(): array
    {
        dd(request());
        return [
            Action::make('kfkf'),
            
        ];
    }


    /* public static function getEloquentQuery(): Builder
    {
        
        return parent::getEloquentQuery()->where(['mois'=> 'Janvier', 'annee'=>2024]);
    } */
 

    
    
}
