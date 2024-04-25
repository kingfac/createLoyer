<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Loyer;
use App\Models\Depense;
use App\Models\Garantie;
use Filament\Forms\Form;
use App\Models\Locataire;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Filament\Tables\Grouping\Group;
use Illuminate\Support\Facades\Blade;
use Filament\Tables\Columns\TextColumn;
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
                    ->relationship(
                        'locataire',
                        modifyQueryUsing: fn (Builder $query) => $query->where('actif', true),
                    )
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->noms} | {$record->occupation->typeOccu->nom} |{$record->num_occupation} ")
                    ->required(),
                Forms\Components\TextInput::make('montant')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(function(Builder $query){
                $locsactifs = DB::table('locataires')->select('id')->where('actif', true);
 
                
                return Garantie::query()->whereIn('locataire_id', $locsactifs)->where('restitution',false)->where('montant', '>', 0);
            })
            ->columns([
                
                Tables\Columns\TextColumn::make('locataire.noms')
                    ->sortable()
                    ->searchable(),
             
                Tables\columns\TextColumn::make('Galerie')
                    ->default(function(Model $record){
                        $galerie = $record->locataire->occupation->galerie->nom;
                        $num_galerie = $record->locataire->occupation->galerie->num;
                        return "$galerie - $num_galerie";
                    }),
                Tables\columns\TextColumn::make('locataire.occupation.typeOccu.nom')->label("Occupation"),
                Tables\columns\TextColumn::make('locataire.num_occupation')->label("Numéro occupation"),
                Tables\Columns\TextColumn::make('montant')
                    ->label('Total garantie')
                    ->summarize(Sum::make('montant')->label('Total')),
                TextColumn::make('Intervenant')
                    ->default(function(Garantie $record){
                        return User::find($record->users_id)->first()->name ?? '';
                    }),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Date')
                    ->sortable()
                
            ])
            ->defaultGroup('locataire.id')->modelLabel("locataire.noms")


            /* ->groups([
                Group::make('locataire.noms')
                    ->collapsible(),
            ]) */
            
            // ->groupsOnly()
            ->filters([
                SelectFilter::make('locataire_nom')->relationship('locataire', 'noms')->label('Locataire'),
                SelectFilter::make('Galerie')->relationship('locataire.occupation.galerie', 'nom')->label('Galerie'),
                SelectFilter::make('Occupation')->relationship('locataire.occupation', 'ref')->label('Occupation(référence)'),
                SelectFilter::make('Type occupation')->relationship('locataire.occupation.typeOccu', 'nom')->label('Type occupation'),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('imprimer')
                    ->action(function(Model $record):void {
                       
                    })
                    ->button()
                    ->icon('heroicon-m-printer')
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

    // public static function getEloquentQuery(): Builder{
    //     return Locataire::query()->where('actif',true);
    // }
}


// return $table
//             ->columns([
                
//                 Tables\Columns\TextColumn::make('locataire.noms')
//                     ->sortable()
//                     ->searchable(),
//                 // Tables\columns\TextColumn::make('llklk')->default(function(Garantie $record){
//                 //     return 'glodi';
//                 // }),
//                 Tables\columns\TextColumn::make('locataire.occupation.galerie.nom'),
//                 Tables\columns\TextColumn::make('locataire.occupation.typeOccu.nom')->label("Occupation"),
//                 Tables\columns\TextColumn::make('locataire.num_occupation')->label("Numéro occupation"),
//                 Tables\Columns\TextColumn::make('montant')
//                     ->summarize(Sum::make('montant')->label('Total'))
//                     ,
//                 /* ToggleColumn::make('restitution'), */
//                 Tables\Columns\TextColumn::make('updated_at')
//                     ->dateTime()
//                     ->sortable()
//                     ->toggleable(isToggledHiddenByDefault: true),
//                     Tables\Columns\TextColumn::make('created_at')
//                     ->dateTime()
//                     ->label('Date')
//                     ->sortable()
                
//             ])
//             ->defaultGroup('locataire.num_occupation')->modelLabel("locataire.noms")

            