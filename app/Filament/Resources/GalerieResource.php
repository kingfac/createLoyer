<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Galerie;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Actions\CreateAction;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Actions;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\GalerieResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\GalerieResource\RelationManagers;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ToggleColumn;

class GalerieResource extends Resource
{
    protected static ?string $model = Galerie::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;



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
                    ->label('Avenue')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('num')
                    ->label('Numéro')
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
                IconColumn::make('actif')
                ->boolean()
            ])
            
            ->filters([
                SelectFilter::make('commune_id')->relationship('commune', 'nom')->label('Commune'),
                SelectFilter::make('actif')
                ->label('Statut')
                ->options([
                    true => 'Actif',
                    false => 'Inactif',
                ])->default(
                    'actif'
                )
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make("Désactiver")
                ->action(function (Galerie $record) {
                    //dd($record->nom);//
                    $record->actif = false;
                    $record->save();
                })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
           ;
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
        return static::getModel()::where('actif', true)->count();
    }


    public static function getActions(){
        return [

        ];
    }







}
