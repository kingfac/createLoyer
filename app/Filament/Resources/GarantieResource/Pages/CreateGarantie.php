<?php

namespace App\Filament\Resources\GarantieResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\GarantieResource;

class CreateGarantie extends CreateRecord
{
    protected static string $resource = GarantieResource::class;


    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index');
    // }

    protected function getCancelFormAction(): Action
    {
        return Action::make('Annuler')->url($this->getResource()::getUrl('index'));
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        //$data['user_id'] = auth()->id();
        $data['users_id'] = Auth::user()->id;
        return $data;
    }


    // protected function afterCreate()
    // {
    
    //         // dd($this->record);

    //     // $loc = Locataire::find($data["locataire_id"]);

    //     $pdf = Pdf::loadHTML(Blade::render('locataire_gar1', ['garantie' => $this->record]))->setPaper('a5','portrait');
    //     Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
        

    //     return response()->download('../public/storage/pdf/doc.pdf');
        
    // }
}
