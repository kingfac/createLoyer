<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocataireResource\Pages;
use App\Filament\Resources\LocataireResource\RelationManagers;
use App\Models\Locataire;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LocataireResource extends Resource
{
    protected static ?string $model = Locataire::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('occupation_id')
                    ->relationship('occupation', 'ref')
                    ->required(),
                Forms\Components\TextInput::make('nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('postnom')
                    ->maxLength(255),
                Forms\Components\TextInput::make('prenom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tel')
                    ->tel()
                    ->required()
                    ->maxLength(14),
                Forms\Components\TextInput::make('garantie')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('actif')
                    ->label('Désactiver/Activer')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('occupation.ref')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('postnom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('prenom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('garantie')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('actif'),
                Tables\Columns\TextColumn::make('created_at')
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
            'index' => Pages\ListLocataires::route('/'),
            'create' => Pages\CreateLocataire::route('/create'),
            'edit' => Pages\EditLocataire::route('/{record}/edit'),
        ];
    }
}
