<?php

namespace App\Filament\Resources\LoyerResource\Pages;

use App\Filament\Resources\LoyerResource;
use Filament\Resources\Pages\Page;

class LoyerGalerie extends Page
{
    protected static string $resource = LoyerResource::class;

    protected static string $view = 'filament.resources.loyer-resource.pages.loyer-galerie';

    public function mount(): void
    {
        static::authorizeResourceAccess();
    }
}
