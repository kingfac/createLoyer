<?php

namespace App\Filament\Resources\LoyerResource\Pages;

use Filament\Actions;
use Livewire\Attributes\On;
use App\Filament\Resources\LoyerResource;
use Filament\Resources\Pages\ListRecords;

class ListLoyers extends ListRecords
{
    protected static string $resource = LoyerResource::class;

    #[On('loyer-created')] 
    public function refresh() {}

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array 
    {
        return [
            LoyerResource\Widgets\CreateLoyerWidget::class,
        ];
    } 

    
}
