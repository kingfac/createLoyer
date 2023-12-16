<?php

namespace App\Filament\Resources\DettesResource\Pages;

use Filament\Actions;
use Livewire\Attributes\On;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\DettesResource;

class ListDettes extends ListRecords
{
    protected static string $resource = DettesResource::class;

    #[On('contact-created')] 
    public function refresh() {}
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array 
    {
        return [
            DettesResource\Widgets\DetteWidget::class,
        ];
    } 
}
