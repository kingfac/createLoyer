<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Galerie;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Locataire;
use App\Models\Occupation;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\LocataireResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LocataireResource\RelationManagers;
use Illuminate\Support\Facades\Event;

class LocataireResource extends Resource
{
    protected static ?string $model = Locataire::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public string $loyer_id;

    public array $gar;

    public static function form(Form $form): Form
    {
        $ddd = ['ggf'=>'hfhf'];
        return $form
            ->schema([
                Forms\Components\Select::make('occupation_id')
                    ->relationship('occupation', )
                    ->reactive()
                    ->live()
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->galerie->nom} | {$record->typeOccu->nom} ({$record->montant} $)")
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
                    ->validationMessages(['regex' => 'Numéro  incorrect', 'required' => 'Ce champ est obligatoire'])
                    ->required()
                    ->maxLength(14),
                Forms\Components\Select::make('nbr')
                    ->label('Nbr mois garantie')
                    ->options( ["3"=>3,"4"=>4,"5"=>5,"6"=>6,"7"=>7,"8"=>8,"9"=>9,"10"=>10])
                    ->reactive(),
                    
                Forms\Components\TextInput::make('garantie')
                    ->numeric()
                    /* ->minValue(fn($get) => Occupation::where('id', $get('occupation_id'))->value('montant') * intval($get('mois'))) */
                    ->default(fn($get) => Occupation::where('id', $get('occupation_id'))->value('montant') * intval($get('mois'))),
                Forms\Components\Toggle::make('actif')
                    ->label('Désactiver/Activer')
                    ->default(true)
                    ->onColor('primary')
                    ->offColor('danger')
                    
            ]);
    }

    public static function table(Table $table): Table
    {

        
        return $table
            ->columns([
                
                Tables\Columns\TextColumn::make('noms')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tel')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('occupation.galerie.nom')
                    ->sortable(),
                Tables\Columns\TextColumn::make('occupation.typeOccu.nom')
                    ->label('Occupation')
                    ->sortable(),
                Tables\Columns\TextColumn::make('garantie')
                    ->label('Garantie ($)')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('occupation.montant')
                    ->label('Loyer ($)')
                    ->money()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('actif'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable()
                
            ])
            ->filters([
                //
                SelectFilter::make('occupation_id')->relationship('occupation', 'galerie.nom')->label('Galerie'),
                //SelectFilter::make('occupation_id')->relationship('occupation', 'typeOccu.nom')->label('Occupation')
            ])
            /* ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('pdf') 
                ->label('Garantie.pdf')
                ->color('success')
                // ->icon('heroicon-s-download')
                ->action(function (Model $record) {
                   
                    $options = [
                        'isHtml5ParserEnabled'=> true,
                        'isPhpEnabled' => true,    
                        'isPhpEnabled'=> true,
                        'isPhpEnabled'=> true,
                        'isHtml5ParserEnabled'=> true,
                        'isHtml5ParserEnabled'=> true,
                    ];
                    
                    $pdf = pdf::loadHTML(Blade::render('factureGarantie', ['record' => $record]));
                    $pdf->save(public_path().'/pdf/doc.pdf');
                    
                    

                    //return response()->view('factureGarantie', ['record' => $record]);
                    
                    
                    
                    //return response()->file(public_path().'/pdf/doc.pdf', ['content-type'=>'application/pdf']);
                    return true;
                })
                ->url('/pdf/doc.pdf', true), 
            ]) */
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('Imprimer la selection')->action(function (Collection $record){
                        // dd($record);
                        return response()->streamDownload(function () use ($record) {
                            echo Pdf::loadHtml(
                                Blade::render('listlocgalerie', ['record' => $record])
                            )->stream();
                        }, random_int(0,1000) . '_list_locataire_galerie.pdf');

                    })->icon('heroicon-o-printer')->color('red')
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


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::all()->count();   
    }

    
}
