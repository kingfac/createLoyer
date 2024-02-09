<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Divers;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\DiversResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DiversResource\RelationManagers;
use Filament\Tables\Filters\Filter;

class DiversResource extends Resource
{
    protected static ?string $model = Divers::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Comptabilité';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                // Forms\Components\Toggle::make('entreprise')
                //     ->label('Entreprise/Locataire')
                //     ->reactive(),
                Grid::make()
                        ->schema([
                            
                            Radio::make('entreprise')
                                ->label('Entreprise/locataire')
                                ->default(true)
                               
                                ->reactive()
                                ->boolean(),
                        ]),
               
                Forms\Components\Select::make('locataire_id')
                    ->hidden(fn(Get $get): bool =>  $get('entreprise') == false)
                    ->relationship('locataire', 'noms')
                    ->required(),
                Forms\Components\TextInput::make('besoin')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('qte')
                    ->label("Quantité")
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('cu')
                    ->label('Coût unitaire')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label("Date")
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Entreprise/Locataire')
                        ->default(function(Divers $record){
                        if($record->entreprise == false){
                            return 'Entreprise';
                        }elseif ($record->entreprise == true) {
                            return $record->locataire->noms;
                        }
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('locataire.occupation.galerie.nom')
                    ->sortable(),
                Tables\Columns\TextColumn::make('locataire.occupation.typeOccu.nom')
                    ->label('Occupation')
                    ->sortable(),
                Tables\Columns\TextColumn::make('besoin')
                    ->label('Besoin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('qte')
                    ->label("Quantité")
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cu')
                    ->label('Coût unitaire')
                    ->numeric()
                    ->sortable()
                    ->money(),
                
               
                   /*  TextColumn::make('total')->default( function(Divers $d){
                        return $d->cu*$d->qte;
                    })->money() */
                    TextColumn::make('total')
                        ->money()
                        ->summarize(
                            Sum::make()
                            ->label('Total général')
                            ->money()
                        )
                        ->label('Total')
                    ,
                    
                // Tables\Columns\TextColumn::make('total')
                //     ->label('cu')
                //     ->summarize()
                //     ->money(),
                        
                        
                    
            ])
            ->filters([
                SelectFilter::make('locataire_id')->relationship('locataire', 'nom')->label('Locataire'),

                SelectFilter::make('Galerie')->relationship('locataire.occupation.galerie', 'nom')->label('Galerie'),

                // SelectFilter::make('locataire_id')->relationship('locataire','locataire_id'),
                // SelectFilter::make('locataire_id')->relationship('locataire', 'locataire.nom')->label('Occupation'),
                // Filter::make('entreprise')->query()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDivers::route('/'),
            'create' => Pages\CreateDivers::route('/create'),
            'edit' => Pages\EditDivers::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::all()->count();   
    }
}
