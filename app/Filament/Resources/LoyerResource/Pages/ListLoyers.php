<?php

namespace App\Filament\Resources\LoyerResource\Pages;

use App\Filament\Resources\LoyerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoyers extends ListRecords
{
    protected static string $resource = LoyerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    
}
