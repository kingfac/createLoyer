<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Divers;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Filament\Resources\DiversResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DiversResource\RelationManagers;
use Filament\Tables\Columns\TextColumn;

class DiversResource extends Resource
{
    protected static ?string $model = Divers::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Comptabilité';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('locataire_id')
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
                Tables\Columns\TextColumn::make('locataire.noms')
                    ->sortable(),
                Tables\Columns\TextColumn::make('locataire.occupation.galerie.nom')
                    ->sortable(),
                Tables\Columns\TextColumn::make('locataire.occupation.typeOccu.nom')
                    ->label('Occupation')
                    ->sortable(),
                Tables\Columns\TextColumn::make('besoin')
                    ->label('Libellé')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('qte')
                //     ->label("Quantité")
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('cu')
                //     ->label('Coût unitaire')
                //     ->numeric()
                //     ->sortable()
                //     ->money(),
                
               
                   /*  TextColumn::make('total')->default( function(Divers $d){
                        return $d->cu*$d->qte;
                    })->money() */
                    TextColumn::make('total')
                        ->money()
                        ->summarize(
                            Sum::make()
                            ->label('Total')
                            ->money()
                        )
                        ->label('Montant')
                    ,
                    
                // Tables\Columns\TextColumn::make('total')
                //     ->label('cu')
                //     ->summarize()
                //     ->money(),
                        
                        
                    
            ])
            ->filters([
                //
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
