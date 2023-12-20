<?php

namespace App\Filament\Resources\TypeOccuResource\Pages;

use App\Filament\Resources\TypeOccuResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTypeOccu extends CreateRecord
{
    protected static string $resource = TypeOccuResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('create');
    }
}
