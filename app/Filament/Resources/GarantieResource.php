<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Loyer;
use App\Models\Garantie;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Grouping\Group;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Filament\Resources\GarantieResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\GarantieResource\RelationManagers;

class GarantieResource extends Resource
{
    protected static ?string $model = Garantie::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Comptabilité';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('locataire_id')
                    ->relationship('locataire')
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->noms} | {$record->occupation->typeOccu->nom} |{$record->num_occupation} ")
                    ->required(),
                Forms\Components\TextInput::make('montant')
                    ->required()
                    ->numeric(),
                // Forms\Components\Toggle::make('restitution')
                //         ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                Tables\Columns\TextColumn::make('locataire.noms')
                    ->sortable()
                    ->searchable(),
                // Tables\columns\TextColumn::make('llklk')->default(function(Garantie $record){
                //     return 'glodi';
                // }),
                Tables\columns\TextColumn::make('locataire.occupation.galerie.nom'),
                Tables\columns\TextColumn::make('locataire.occupation.typeOccu.nom')->label("Occupation"),
                Tables\columns\TextColumn::make('locataire.num_occupation')->label("Numéro occupation"),
                Tables\Columns\TextColumn::make('montant')
                    ->summarize(Sum::make('montant')->label('Total'))
                    ,
                /* ToggleColumn::make('restitution'), */
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Date')
                    ->sortable()
                
            ])
            // ->groups(['locataire.noms','locataire.occupation.typeOccu.nom'])
            ->defaultGroup('locataire.num_occupation')->modelLabel("locataire.noms")
            /* ->groups([
                Group::make('locataire.noms')
                    ->collapsible(),
            ]) */
            
            // ->groupsOnly()
            ->filters([
                SelectFilter::make('locataire_nom')->relationship('locataire', 'noms')->label('Locataire'),

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
            'index' => Pages\ListGaranties::route('/'),
            'create' => Pages\CreateGarantie::route('/create'),
            'edit' => Pages\EditGarantie::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::all()->count();   
    }

    public static function getEloquentQuery(): Builder{
        return static::getModel()::query()->where('restitution',false);
    }
}
