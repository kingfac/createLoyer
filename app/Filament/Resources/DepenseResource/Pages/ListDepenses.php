<?php

namespace App\Filament\Resources\DepenseResource\Pages;

use App\Filament\Resources\DepenseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDepenses extends ListRecords
{
    protected static string $resource = DepenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
