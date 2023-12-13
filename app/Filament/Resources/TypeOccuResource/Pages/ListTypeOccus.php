<?php

namespace App\Filament\Resources\TypeOccuResource\Pages;

use App\Filament\Resources\TypeOccuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTypeOccus extends ListRecords
{
    protected static string $resource = TypeOccuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
