<?php

namespace App\Filament\Resources;

use Closure;
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
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Doctrine\DBAL\Types\TextType;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ReplicateAction;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\LocataireResource\Pages;
use App\Filament\Resources\LocataireResource\Pages\CreateLocataire;
use App\Filament\Resources\LocataireResource\Pages\EditLocataire;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\LocataireResource\RelationManagers;
use GuzzleHttp\Promise\Create;

class LocataireResource extends Resource
{
    protected static ?string $model = Locataire::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Gestion Locataires';
    // protected static ?string $navigationLabel = 'Locataires actifs';
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
                    ->required()
                    ->validationMessages([ 'required' => 'Ce champ est obligatoire'])
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->galerie->nom} | {$record->typeOccu->nom} ({$record->montant} $)")
                    ->required(),
                Forms\Components\TextInput::make('num_occupation')
                    ->label('Numéro occupation')
                    ->required(),
                Forms\Components\TextInput::make('nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('postnom')
                    ->label('Post-nom')
                    ->maxLength(255)
                    ->required(),
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
                    ->hiddenOn(Pages\EditLocataire::class)
                    ->options( ["3"=>3,"4"=>4,"5"=>5,"6"=>6,"7"=>7,"8"=>8,"9"=>9,"10"=>10])
                    ->reactive()
                    ->required(),
                Forms\Components\TextInput::make('garantie')
                    ->numeric()
                    ->hiddenOn([CreateLocataire::class,EditLocataire::class])
                    ->required(),

                    /* ->minValue(fn($get) => Occupation::where('id', $get('occupation_id'))->value('montant') * intval($get('mois'))) */
                    // ->default(fn($get) => Occupation::where('id', $get('occupation_id'))->value('montant') * intval($get('mois'))),
                
                Forms\Components\Select::make('mp')
                    ->label('Mois du premier paiement')
                    ->options( ["1"=> "janvier","2"=>"février", "3"=>"mars","4"=>"avril","5"=>"mai","6"=>"juin","7"=>"juillet","8"=>"aout","9"=>"septembre","10"=>"octobre","11" => "novembre", "12" => "décembre"])
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('ap')
                    ->label('Année du premier paiement')
                    ->options(function(){
                            
                        return [
                            '2023' => 2023,
                            '2024' => 2024,
                            '2025' => 2025,
                            '2026' => 2026,
                            '2027' => 2027,
                            '2028' => 2028,
                            '2029' => 2029,
                            '2030' => 2030,
                        ];
                    })
                    ->default($currentDate->format("Y"))
                    ->required(),
                    
                Forms\Components\Toggle::make('actif')
                    ->label('Désactiver/Activer')
                    ->default(true)
                    
                    
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
                
                Tables\Columns\TextColumn::make('Galerie')
                    ->default(function(Model $record){
                        $galerie = $record->occupation->galerie->nom;
                        $num_galerie = $record->occupation->galerie->num;
                        return "$galerie - $num_galerie";
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('occupation.typeOccu.nom')
                    ->label('Occupation')
                    ->sortable(),
                Tables\Columns\TextColumn::make('num_occupation')
                    ->label('Numéro occupation')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jjj')
                    ->label('Garantie ($)')
                    /* ->money() */
                    ->default(function(Locataire $record){
                        // dd($record);
                        $locataire = Locataire::where(['id'=> $record->id, 'num_occupation' => $record->num_occupation])->get();
                        if($locataire[0]->actif){
                            return Garantie::where(['locataire_id' => $locataire[0]->id, 'restitution' => false])->sum('montant');
                        }
                        else{
                            return 'Restituée';
                        }
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('occupation.montant')
                    ->label('Loyer ($)')
                    ->money()
                    ->sortable(),
                Tables\Columns\IconColumn::make('actif'),
                Tables\Columns\TextColumn::make('Mois premier paiement')
                    ->default(function(Model $record){
                        $ConvMois = [
                            1 => "janvier",
                            2 =>"février", 
                            3 =>"mars",
                            4 =>"avril",
                            5 =>"mai",
                            6 =>"juin",
                            7 =>"juillet",
                            8 =>"aout",
                            9 =>"septembre",
                            10 =>"octobre",
                            11  => "novembre", 
                            12  => "décembre"
                        ];
                        // dd($record->mp);
                        if ($record->mp != 0 || $record->mp != null) {
                            # code...
                            // dd($ConvMois[intval($record->mp)]);
                            return $ConvMois[$record->mp];
                        }

                        return "Non spécifié";
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
                
            ])
            ->filters([
                //
                //SelectFilter::make('occupation_id')->relationship('occupation', 'galerie.nom')->label('Galerie'),
                SelectFilter::make('Galerie')->relationship('occupation','galerie.nom'),
                SelectFilter::make('occupation_id')->relationship('occupation', 'typeOccu.nom')->label('Occupation'),
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                // Tables\Actions\Action::make('pdf') 
                // ->label('Garantie.pdf')
                // ->color('success')
                // // ->icon('heroicon-s-download')
                // ->action(function (Model $record) {
                   
                //     $options = [
                //         'isHtml5ParserEnabled'=> true,
                //         'isPhpEnabled' => true,    
                //         'isPhpEnabled'=> true,
                //         'isPhpEnabled'=> true,
                //         'isHtml5ParserEnabled'=> true,
                //         'isHtml5ParserEnabled'=> true,
                //     ];
                    
                //     $pdf = pdf::loadHTML(Blade::render('factureGarantie', ['record' => $record]));
                //     $pdf->save(public_path().'/pdf/doc.pdf');
                    
                    

                    //return response()->view('factureGarantie', ['record' => $record]);
                    
                    
                    
                    //return response()->file(public_path().'/pdf/doc.pdf', ['content-type'=>'application/pdf']);
                //     return true;
                // })
                // ->url('/pdf/doc.pdf', true), 
                Action::make('dupliquer')
                    ->form([
                        Select::make('occupation_id')
                            ->relationship('occupation' ,)
                            ->label('Numéro occupation')
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->galerie->nom} | {$record->typeOccu->nom} ({$record->montant} $)"),
                        TextInput::make('num_occupation')
                            ->required(),
                        Forms\Components\Select::make('nbr')
                            ->label('Nombre de mois garantie')
                            ->options( ["3"=>3,"4"=>4,"5"=>5,"6"=>6,"7"=>7,"8"=>8,"9"=>9,"10"=>10]),
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
                            ->default(NOW()->format("Y"))
                            ->inlineLabel()
                            ->required(),
                            
                        Forms\Components\Toggle::make('actif')
                            ->label('Désactiver/Activer')
                            ->default(true)
                            ->onColor('primary')
                            ->offColor('danger')
                            

                        ])
                    ->action(function(array $data, Locataire $record){
                        // dd($record);
                        /* verifications befor created garantie */
                        $loyer = Occupation::where('id', $data['occupation_id'])->first();
                        
                        if($data['garantie'] == 0 && $data['nbr'] == null){
                            /* Notification::make()
                            ->warning()
                            ->title("Validation données erreur")
                            ->body("Garantie ou mois garantie doit être renseigné pour valider
                            le formulaire")
                            ->persistent()
                            
                            ->send();
                            $this->halt(); */
                            //Rien A faire pena awa
                            $data['nbr'] = 0;
                            $data['garantie'] = 0;
                        }
                        
                        elseif($data['garantie'] == 0 && $data['nbr'] != null){
                            $data['garantie'] = $loyer->montant * intval($data['nbr']);
                        }
                        elseif($data['garantie'] != 0 && $data['nbr'] == null){
                            $data['nbr'] = 0;
                        }
                        $locataire = Locataire::create([
                            'nom' => $record->nom,
                            'postnom' => $record->postnom,
                            'occupation_id' => $data['occupation_id'],
                            'prenom' => $record->prenom,
                            'tel' => $record->tel,
                            'garantie' => $data['garantie'],
                            'nbr' => $data['nbr'],
                            'mp' => $data['mp'],
                            'num_occupation' => $data['num_occupation'],
                            'ap' => $data['ap'],
                            'actif' => $data['actif'],

                        ]);

                        if($data['garantie'] > 0){
                            // dd($data['garantie']);
                            Garantie::create(['locataire_id' => $locataire->id, 'montant' => $data['garantie']]);
                        }

                        Notification::make()
                            ->title('Locataire')
                            ->body('Locataire crée avec succès!')
                            ->success()
                            ->icon('')
                            ->iconColor('')
                            ->duration(5000)
                            ->persistent()
                            ->actions([
                                
                            ])
                            ->send();
                    })
               
            ]) 
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
        return static::getModel()::where('actif', true)->count();   
    }


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('actif', true)->orderBy('noms');
    }

    
}
