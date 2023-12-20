<?php

namespace App\Filament\Resources\OccupationResource\Pages;

use App\Filament\Resources\OccupationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOccupation extends CreateRecord
{
    protected static string $resource = OccupationResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('create');
    }
}
