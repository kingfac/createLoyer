<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Loyer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LoyerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LoyerResource\RelationManagers;
use App\Models\Locataire;

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
                        ->relationship('locataire', 'noms')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('mois')->options(['Janvier' => 'Janvier','Février' => 'Février','Mars' => 'Mars','Avril' => 'Avril','Mais' => 'Mais','Juin' => 'Juin','Juillet' => 'Juillet','Aout' => 'Aout','Septembre' => 'Septembre','Octobre' => 'Octobre','Novembre' => 'Novembre','Décembre' => 'Décembre'])
                        ->required(),
                    Forms\Components\TextInput::make('annee')
                        ->label('Année')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('montant')
                        ->required(),
                    Forms\Components\Toggle::make('garantie')
                        ->label('Utiliser la garantie'),
                ])->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        $ans = [];
        for ($i = 2009; $i <= 2030; $i++) {
            $ans[$i] = $i;
        }
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('locataire.noms')
                    ->sortable(),
                Tables\Columns\TextColumn::make('locataire.occupation.ref')
                    ->sortable(),
                Tables\Columns\TextColumn::make('locataire.occupation.typeOccu.nom')
                    ->sortable(),
                Tables\Columns\TextColumn::make('mois')
                    ->searchable(),
                Tables\Columns\TextColumn::make('annee')
                    ->label('Année')
                    ->searchable(),
                Tables\Columns\TextColumn::make('montant')
                    ->label('Montant')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('locataire.garantie')
                    ->label('Reste Garantie')
                    ->money()
                    ->sortable(),
                Tables\Columns\IconColumn::make('garantie')
                    ->label('Avec garantie')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    //->label('Date de payement')
                    ->sortable()
            ])
            ->defaultSort('created_at', 'desc')
            ->searchable()
            
            ->filters([
                //
                SelectFilter::make('mois')->options(['Janvier' => 'Janvier','Février' => 'Février','Mars' => 'Mars','Avril' => 'Avril','Mais' => 'Mais','Juin' => 'Juin','Juillet' => 'Juillet','Aout' => 'Aout','Septembre' => 'Septembre','Octobre' => 'Octobre','Novembre' => 'Novembre','Décembre' => 'Décembre']),
                SelectFilter::make('annee')->options($ans),
                SelectFilter::make('locataire_id')->relationship('locataire', 'noms')
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('pdf') 
                ->label('PDF')
                ->icon('heroicon-o-printer')
                ->color('success')
                // ->icon('heroicon-s-download')
                ->action(function (Model $record) {
                    return response()->streamDownload(function () use ($record) {
                        echo Pdf::loadHtml(
                            Blade::render('pdf', ['record' => $record])
                        )->stream();
                    }, $record->id.'1.pdf');
                }), 

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
            //'create' => Pages\CreateLoyer::route('/create'),
            'edit' => Pages\EditLoyer::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            LoyerResource\Widgets\CreateLoyerWidget::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::all()->count();   
    }
}
