<?php

namespace App\Filament\Resources\LocataireResource\Pages;

use App\Filament\Resources\LocataireResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLocataire extends CreateRecord
{
    protected static string $resource = LocataireResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('create');
    }
}
