<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Occupation;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OccupationResource\Pages;
use App\Filament\Resources\OccupationResource\RelationManagers;

class OccupationResource extends Resource
{
    protected static ?string $model = Occupation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('galerie_id')
                    ->relationship('galerie', )
                    ->reactive()
                    ->live()
                    ->getOptionLabelFromRecordUsing(fn (Model $record) =>  "{$record->nom} - {$record->num} ")
                    ->required(),
                Forms\Components\Select::make('type_occu_id')
                    ->relationship('typeOccu', 'nom')
                    ->label('Type occupation')
                    ->required(),
                Forms\Components\TextInput::make('ref')
                    ->label('Référence')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('montant')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('multiple')
                    ->label('Occupation multiple')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('galerie.nom')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('typeOccu.nom')
                    ->numeric()
                    ->label('Type Occupation')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ref')
                    ->label('Référence')
                    ->searchable(),
                Tables\Columns\TextColumn::make('montant')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('multiple')
                    ->boolean(),
                Tables\Columns\IconColumn::make('actif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('montant')
                    ->summarize(Sum::make()
                    ->label('Total'))
            ])
            ->filters([
                SelectFilter::make('galerie_id')->relationship('galerie', 'nom')->label('Galerie'),
                SelectFilter::make('type_occu_id')->relationship('typeOccu', 'nom')->label('Type occupation'),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOccupations::route('/'),
            'create' => Pages\CreateOccupation::route('/create'),
            'edit' => Pages\EditOccupation::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::all()->count();   
    }
    
    
}
