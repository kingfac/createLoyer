<?php

namespace App\Filament\Pages;

use App\Models\Garantie;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\Locataire;
use Filament\Tables\Table;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Concerns\InteractsWithTable;

class AncienLocataire extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Gestion Locataires';
    protected static string $view = 'filament.pages.ancien-locataire';
    protected static ?string $navigationLabel='Anciens locataires';

    public static function table(Table $table):Table {
        
        return $table
            ->query(function(){
                return Locataire::query()->where('actif', false);
            })
            ->columns([
                
                TextColumn::make('noms')
                    ->searchable(),
                TextColumn::make('tel')
                    ->searchable(),
                
                TextColumn::make('Galerie')
                    ->default(function(Model $record){
                        $galerie = $record->occupation->galerie->nom;
                        $num_galerie = $record->occupation->galerie->num;
                        return "$galerie - $num_galerie";
                    })
                    ->sortable(),
                TextColumn::make('occupation.typeOccu.nom')
                    ->label('Occupation')
                    ->sortable(),
                TextColumn::make('num_occupation')
                    ->label('Numéro occupation')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('jjj')
                    ->label('Montant restitué($)')
                    /* ->money() */
                    ->default(function(Locataire $record){
                        // dd($record);
                        $montant = Garantie::where(['locataire_id'=> $record->id, 'restitution'=>true])->get();
                        return $montant->value('montant').'$';
                    })
                    ->sortable(),
                TextColumn::make('occupation.montant')
                    ->label('Loyer ($)')
                    ->money()
                    ->sortable(),
                IconColumn::make('actif'),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('mp')
                    ->dateTime("M")
                    ->sortable(),
                    
                
            ])
            ->filters([
                //
                //SelectFilter::make('occupation_id')->relationship('occupation', 'galerie.nom')->label('Galerie'),
                SelectFilter::make('Galerie')->relationship('occupation','galerie.nom'),
                SelectFilter::make('occupation_id')->relationship('occupation', 'typeOccu.nom')->label('Occupation'),
            ])
            ->actions([
               
              
               
            ]) 
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                //     Tables\Actions\BulkAction::make('Imprimer la selection')->action(function (Collection $record){
                //         // dd($record);
                //         return response()->streamDownload(function () use ($record) {
                //             echo Pdf::loadHtml(
                //                 Blade::render('listlocgalerie', ['record' => $record])
                //             )->stream();
                //         }, random_int(0,1000) . '_list_locataire_galerie.pdf');

                //     })->icon('heroicon-o-printer')->color('red')
                // ]),
            ]);
            
           
        
    }
    // protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string
    {
        return Locataire::all()->where('actif', false)->count();   
    }
}
