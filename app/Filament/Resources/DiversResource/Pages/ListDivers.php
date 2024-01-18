<?php

namespace App\Filament\Resources\DiversResource\Pages;

use App\Filament\Resources\DiversResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDivers extends ListRecords
{
    protected static string $resource = DiversResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
