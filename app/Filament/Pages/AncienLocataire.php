<?php

namespace App\Filament\Pages;

use App\Models\Garantie;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\Locataire;
use Filament\Tables\Table;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Concerns\InteractsWithTable;

class AncienLocataire extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Gestion Locataires';
    protected static string $view = 'filament.pages.ancien-locataire';
    protected static ?string $navigationLabel='Anciens locataires';

    public static function table(Table $table):Table {
        
        return $table
                ->query(Locataire::where('actif', false))
                ->columns([
                TextColumn::make('matricule'),
                TextColumn::make('noms')
                    ->searchable(),
                TextColumn::make('tel')
                    ->searchable(),
                
                TextColumn::make('Galerie')
                    ->default(function(Model $record){
                        $galerie = $record->occupation->galerie;
                        $num_galerie = $record->occupation->galerie->num;
                        return "$galerie - $num_galerie";
                    })
                    ->sortable(),
                TextColumn::make('occupation.typeOccu.nom')
                    ->label('Occupation')
                    ->sortable(),
                TextColumn::make('num_occupation')
                    ->label('Numéro occupation')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('jjj')
                    ->label('Montant restitué($)')
                    ->default(function(Locataire $record){
                        $montant = Garantie::where(['locataire_id'=> $record->id, 'restitution'=>true])->get();
                        return $montant->value('montant').'$';
                    })
                    ->sortable(),
                TextColumn::make('occupation.montant')
                    ->label('Loyer ($)')
                    ->money()
                    ->sortable(),
                IconColumn::make('actif'),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('mp')
                    ->dateTime("M")
                    ->sortable(),
                    
                
            ])
            ->filters([
                //SelectFilter::make('occupation_id')->relationship('occupation', 'galerie.nom')->label('Galerie'),
                // SelectFilter::make('Galerie')->relationship('occupation','galerie.nom'),
                // SelectFilter::make('occupation_id')->relationship('occupation', 'typeOccu.nom')->label('Occupation'),
            ])
            ->actions([
               
              
               
            ]) 
            ->bulkActions([
            ]);
            
           
        
    }
    // protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string
    {
        return Locataire::all()->where('actif', false)->count();   
    }
}
