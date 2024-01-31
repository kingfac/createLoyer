<?php

namespace App\Filament\Resources;

use DateTime;
use Filament\Forms;
use Filament\Tables;
use App\Models\Galerie;
use Filament\Forms\Set;
use App\Models\Garantie;
use Filament\Forms\Form;
use App\Models\Locataire;
use App\Models\Occupation;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\LocataireResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LocataireResource\RelationManagers;

class LocataireResource extends Resource
{
    protected static ?string $model = Locataire::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 5;

    public string $loyer_id;

    public array $gar;

    public static function form(Form $form): Form
    {
        $ddd = ['ggf'=>'hfhf'];
        $currentDate = new DateTime();
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
                    ->label('Post-nom')
                    ->maxLength(255),
                Forms\Components\TextInput::make('prenom')
                    ->label('Prénom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tel')
                    ->label('Téléphone')
                    ->tel()
                    ->validationMessages(['regex' => 'Numéro  incorrect', 'required' => 'Ce champ est obligatoire', 'min' => 'Numéro incorrect.'])
                    ->required()
                    ->minLength(10)
                    ->maxLength(14),
                Forms\Components\Select::make('nbr')
                    ->label('Nombre de mois garantie')
                    ->options( ["3"=>3,"4"=>4,"5"=>5,"6"=>6,"7"=>7,"8"=>8,"9"=>9,"10"=>10])
                    ->reactive(),
                Forms\Components\TextInput::make('garantie')
                    ->numeric()
                    /* ->minValue(fn($get) => Occupation::where('id', $get('occupation_id'))->value('montant') * intval($get('mois'))) */
                    ->default(fn($get) => Occupation::where('id', $get('occupation_id'))->value('montant') * intval($get('mois'))),
                Forms\Components\Select::make('mp')
                    ->label('Mois du premier paiement')
                    ->options( ["1"=> "janvier","2"=>"février", "3"=>"mars","4"=>"avril","5"=>"mai","6"=>"juin","7"=>"juillet","8"=>"aout","9"=>"septembre","10"=>"octobre","11" => "novembre", "12" => "décembre"])
                    ->reactive(),
                Forms\Components\TextInput::make('ap')
                    ->label('Année du premier paiement')
                    ->numeric()
                    ->maxValue(2030)
                    ->minValue(2023)
                    ->default($currentDate->format("Y"))
                    ->inlineLabel()
                    ->required(),
                    
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
                Tables\Columns\TextColumn::make('jjj')
                    ->label('Garantie ($)')
                    /* ->money() */
                    ->default(function(Locataire $record){
                        return Garantie::where(['locataire_id' => $record->id, 'restitution' => false])->sum('montant');
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('occupation.montant')
                    ->label('Loyer ($)')
                    ->money()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('actif'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mp')
                    ->dateTime("M")
                    ->sortable(),
                
            ])
            ->filters([
                //
                //SelectFilter::make('occupation_id')->relationship('occupation', 'galerie.nom')->label('Galerie'),
                SelectFilter::make('Galerie')->relationship('occupation','galerie.nom'),
                SelectFilter::make('occupation_id')->relationship('occupation', 'typeOccu.nom')->label('Occupation'),
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
