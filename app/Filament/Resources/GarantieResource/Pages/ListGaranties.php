<?php

namespace App\Filament\Resources\GarantieResource\Pages;

use App\Models\Loyer;
use Filament\Actions;
use App\Models\Garantie;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GarantieResource;

class ListGaranties extends ListRecords
{
    protected static string $resource = GarantieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('Restituer garantie')
                ->form([
                    
                    Select::make('locataire_id')
                        ->relationship('locataire')
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->noms} | {$record->occupation->typeOccu->nom} |{$record->num_occupation} ")
                        ->required()
                        ->validationMessages(['required' => 'Veuillez séléctionner un locataire']),

                    ])
                ->action(function(array $data){
                    // dd($data['locataire_id']);
                    // dd($g);
                    $paiements = Loyer::where('locataire_id', $data['locataire_id'])->where('garantie',true)->sum('montant');
                    $r_exist = Garantie::where(['locataire_id'=>$data['locataire_id'], 'restitution'=> true])->first();
                    if($r_exist == null){
                        
                        // dd($record->montant);
                        $garanties = Garantie::where('locataire_id',$data['locataire_id'])->sum('montant');
                        // dd($garanties, $paiements);
                        $restitution = $garanties-$paiements;
                        // dd($restitution);
    
                        $restitution = Garantie::create([
                            'montant' => $restitution,
                            'locataire_id' => $data['locataire_id'],
                            'restitution' => true,
                        ]);
                        $g = Garantie::where('locataire_id',$data['locataire_id'])->orderBy('restitution')->get();

                        return response()->streamDownload(function () use ($g, $paiements) {
                            echo Pdf::loadHtml(
                                Blade::render('restitution', ['data' => $g, 'loyers'=> $paiements])
                            )->stream();
                        }, '1.pdf'); 
    
                    }
                    else{
                        Notification::make()
                        ->title('Erreur de restitution')
                            ->body('Ce locataire a déjà été restitué.')
                            ->danger()
                            ->icon('')
                            ->iconColor('')
                            ->duration(5000)
                            ->persistent()
                            ->actions([
                                
                                ])
                                ->send();
                    }
                })
        ];
    }
}
