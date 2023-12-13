<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoyerResource\Pages;
use App\Filament\Resources\LoyerResource\RelationManagers;
use App\Models\Loyer;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoyerResource extends Resource
{
    protected static ?string $model = Loyer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([

                    Forms\Components\Select::make('locataire_id')
                        ->relationship('locataire', 'nom')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('mois')->options(['Janvier' => 'Janvier','Février' => 'Février','Mars' => 'Mars','Avril' => 'Avril','Mais' => 'Mais','Juin' => 'Juin','Juillet' => 'Juillet','Aout' => 'Aout','Septembre' => 'Septembre','Octobre' => 'Octobre','Novembre' => 'Novembre','Décembre' => 'Décembre'])
                        ->required(),
                    Forms\Components\TextInput::make('annee')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('montant')
                        ->required()
                        ->numeric(),
                    Forms\Components\Toggle::make('garantie')
                        ->label('Utiliser la garantie')

                ])->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('locataire.nom')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mois')
                    ->searchable(),
                Tables\Columns\TextColumn::make('annee')
                    ->searchable(),
                Tables\Columns\TextColumn::make('montant')
                    ->numeric()
                    ->sortable(),
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
                SelectFilter::make('mois')->options(['Janvier' => 'Janvier','Février' => 'Février','Mars' => 'Mars','Avril' => 'Avril','Mais' => 'Mais','Juin' => 'Juin','Juillet' => 'Juillet','Aout' => 'Aout','Septembre' => 'Septembre','Octobre' => 'Octobre','Novembre' => 'Novembre','Décembre' => 'Décembre']),
                SelectFilter::make('annee')->options(range(2009,2030)),
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
            'index' => Pages\ListLoyers::route('/'),
            'create' => Pages\CreateLoyer::route('/create'),
            'edit' => Pages\EditLoyer::route('/{record}/edit'),
        ];
    }
}
