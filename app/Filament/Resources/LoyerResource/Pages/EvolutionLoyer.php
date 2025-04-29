<?php

namespace App\Filament\Resources\LoyerResource\Pages;

use App\Filament\Resources\LoyerResource;
use App\Models\Locataire;
use Filament\Resources\Pages\Page;


class EvolutionLoyer extends Page
{
    public $data;
    public $mois;
    public $annee;

    protected static string $resource = LoyerResource::class;


    protected static string $view = 'filament.resources.loyer-resource.pages.evolution-loyer';
    protected static ?string $title = '';

    public function mount($mois): void
    {
        $this->mois = $mois;
        $this->data = Locataire::where('actif', true)->get();
        //static::authorizeResourceAccess();
        // static::authorizeResourceAccess();
        //dd($mois);
    }

    protected function getHeaderWidgets(): array
    {
        
        return [

        ];
    }

    public function getBreadcrumb(): ?string
    {
        return null;
    }
}
