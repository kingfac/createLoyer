<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Depense;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Filament\Resources\DepenseResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DepenseResource\RelationManagers;
use App\Models\User;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class DepenseResource extends Resource
{
    protected static ?string $model = Depense::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Comptabilité';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Textarea::make('observation')
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
                Tables\Columns\TextColumn::make('besoin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('qte')
                    ->label("Quantité")
                    ->numeric()
                    ->sortable(),               
                Tables\Columns\TextColumn::make('cu')
                    ->label('Coût unitaire')
                    ->money(),
                /* Tables\Columns\TextColumn::make('Total')->default(function(Depense $record){
                    return $record->cu*$record->qte;
                })->money() */
                Tables\Columns\TextColumn::make('total')
                    ->money()
                    ->summarize(Sum::make()->label('Total')->money()),
                TextColumn::make('Intervenant')
                    ->default(function(Depense $record){
                        return User::find($record->users_id)->first()->name ?? '';
                    }),
                TextColumn::make('observation')
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
            'index' => Pages\ListDepenses::route('/'),
            'create' => Pages\CreateDepense::route('/create'),
            'edit' => Pages\EditDepense::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::all()->count();   
    }
}
