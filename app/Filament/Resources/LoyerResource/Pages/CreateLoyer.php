<?php

namespace App\Filament\Resources\LoyerResource\Pages;

use App\Filament\Resources\LoyerResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Actions\Action as ActionsAction;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateLoyer extends CreateRecord
{
    protected static string $resource = LoyerResource::class;

    protected function getActions(): array
    {
        return [
            //Action::make('create')->requiresConfirmation()
        ];
    }

    protected function getFormActions(): array
    {
        return [
            CreateAction::make()->requiresConfirmation()->action(function ($data){
                dd($data);
            }),
        ];
    }
}
