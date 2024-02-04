<?php

namespace App\Livewire;

use App\Models\Galerie;
use Livewire\Component;
use App\Models\Garantie;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Query\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
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
                TextColumn::make('locataire.occupation.galerie.nom'),
                TextColumn::make('locataire.occupation.typeOccu.nom')
                    ->label('Type occupation'),
                TextColumn::make('locataire.num_occupation')
                    ->label('Numéro occupation'),
                TextColumn::make('montant')
                    ->label('Dette'),
                TextColumn::make('created_at')
                    ->label('Date de sortie')
            ]);
    }

    // public function form(Form $form): Form{
    //     return $form    
    //             ->schema([
    //                 TextInput::make('restitution')
    //             ]);
    // }

   

}
