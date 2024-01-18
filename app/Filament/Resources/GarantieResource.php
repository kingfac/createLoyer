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
use Illuminate\Support\Facades\Blade;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('locataire_id')
                    ->relationship('locataire','noms')
                    ->required(),
                Forms\Components\TextInput::make('montant')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('restitution')
                        ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                Tables\Columns\TextColumn::make('locataire.nom')
                    ->sortable()
                    ->searchable(),
                // Tables\columns\TextColumn::make('llklk')->default(function(Garantie $record){
                //     return 'glodi';
                // }),
                Tables\columns\TextColumn::make('locataire.occupation.typeOccu.nom'),
                Tables\Columns\TextColumn::make('montant')
                    ->summarize(Sum::make('montant')),
                ToggleColumn::make('restitution'),
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
            ->defaultGroup('locataire.noms')
            // ->groupsOnly()
            ->filters([
                SelectFilter::make('locataire_nom')->relationship('locataire', 'noms')->label('Locataire'),

            ])
            ->actions([
                Action::make('Restituer')
                ->action(function(Garantie $record){
                    
                    // dd($g);
                    $paiements = Loyer::where('locataire_id', $record->locataire_id)->where('garantie',true)->sum('montant');
                    $r_exist = Garantie::where(['locataire_id'=>$record->locataire_id, 'restitution'=> true])->first();
                    if($r_exist == null){
                        
                        // dd($record->montant);
                        $garanties = Garantie::where('locataire_id',$record->locataire_id)->sum('montant');
                        // dd($garanties, $paiements);
                        $restitution = $garanties-$paiements;
                        // dd($restitution);
    
                        $restitution = Garantie::create([
                            'montant' => $restitution,
                            'locataire_id' => $record->locataire_id,
                            'restitution' => true,
                        ]);
    
                    }
                    else{
                        Notification::make()
                        ->title('Erreur de restitution')
                            ->body('Ce locataire a déjà été restitué.')
                            ->danger()
                            ->icon('')
                            ->iconColor('')
                            ->duration(5000)
                            ->persistent()
                            ->actions([
                                
                                ])
                                ->send();
                    }
                    
                    $g = Garantie::where('locataire_id',$record->locataire_id)->orderBy('restitution')->get();
                    // $pdf = Pdf::loadHTML(Blade::render('restitution', ['data' => $g]));
                    //Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
                    return response()->streamDownload(function () use ($g, $paiements) {
                        echo Pdf::loadHtml(
                            Blade::render('restitution', ['data' => $g, 'loyers'=> $paiements])
                        )->stream();
                    }, '1.pdf');                }),

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

    // public static function getEloquentQuery(): Builder{
    //     return static::getModel()::query()->where('restitution',true);
    // }
}
