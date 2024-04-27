<?php

namespace App\Livewire;

use App\Models\Galerie;
use Livewire\Component;
use App\Models\Garantie;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Query\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;


class SortieDette extends Component implements HasForms,HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;


    
    public function render()
    {
        return view('livewire.sortie-dette');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Garantie::where('restitution', true)->where('montant','<', 0))
            ->columns([
                TextColumn::make('locataire.noms'),
                TextColumn::make('Galerie')
                    ->default(function(Garantie $record){
                        $gal = $record->locataire->occupation->galerie->nom. '-'.$record->locataire->occupation->galerie->num;
                        return $gal;
                    }),
                TextColumn::make('locataire.occupation.typeOccu.nom')
                    ->label('Type occupation'),
                TextColumn::make('locataire.num_occupation')
                    ->label('Numéro occupation'),
                TextColumn::make('montant')
                    ->label('Dette'),
                TextColumn::make('created_at')
                    ->label('Date de sortie'),
                TextColumn::make('montant')
                    ->money()
                    ->summarize(
                        Sum::make()
                        ->label('Total général')
                        ->money()
                    )
                    ->label('Total')
            ]);
    }

    // public function form(Form $form): Form{
    //     return $form    
    //             ->schema([
    //                 TextInput::make('restitution')
    //             ]);
    // }

   

}
