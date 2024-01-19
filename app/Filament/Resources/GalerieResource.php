<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Galerie;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Actions;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\GalerieResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\GalerieResource\RelationManagers;

class GalerieResource extends Resource
{
    protected static ?string $model = Galerie::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;
    
    protected static ?string $navigationGroup = 'Comptabilité';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('commune_id')
                    ->relationship('commune', 'nom')
                    ->default(8)
                    ->required(),
                Forms\Components\TextInput::make('nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('av')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('num')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('commune.nom')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('av')
                    ->label('Avenue')
                    ->searchable(),
                Tables\Columns\TextColumn::make('num')
                    ->label('Numéro')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListGaleries::route('/'),
            'create' => Pages\CreateGalerie::route('/create'),
            'edit' => Pages\EditGalerie::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::all()->count();   
    }


    public static function getActions(){
        return [
            
        ];
    }


    

    

    
}
