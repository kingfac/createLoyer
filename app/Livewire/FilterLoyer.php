<?php

namespace App\Livewire;

use DateTime;
use Livewire\Component;
use Filament\Forms\Form;
use App\Models\Locataire;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class FilterLoyer extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $annee;
    public $mois;

    protected $listeners = ['m4a' => '$refresh'];

    public function render()
    {
        return view('livewire.filter-loyer');
    }


    #[On('m4')] 
    public function update($mois, $annee)
    {
        // ...
        $this->annee = $annee;
        $this->mois = $mois;
        $this->dispatch('m4a');
    }

    
    
    public function table(Table $table): Table
    {
        $ans = [];
        for ($i = 2009; $i <= 2030; $i++) {
            $ans[$i] = $i;
        }
        return $table
            ->query(Locataire::query()
            /* ->select(['locataires.*', 'loyers.montant', 'loyers.mois'])
            ->join('loyers', 'loyers.locataire_id', '=', 'locataires.id', 'LEFT OUTER') */
            ->join('loyers', 'loyers.locataire_id', '=', 'locataires.id', 'LEFT OUTER')
            ->withSum(
                [
                    'loyers' => fn($query) => $query->where(['mois'=>$this->mois, 'annee'=>$this->annee])
                ], 
                'montant'
            )
            ->groupBy('locataires.id')
            )
            
            ->columns([
                TextColumn::make('S')
                    ->colors([
                        
                        'success' => static fn ($record): bool => $record->occupation->montant == $record->loyers_sum_montant,
                        'danger' => static fn ($record): bool => $record->loyers_sum_montant == 0,
                        'info' => static fn ($record): bool => $record->loyers_sum_montant > 0 && $record->loyers_sum_montant < $record->occupation->montant,
                        /* 'success' => static fn ($record): bool => $state === 'published',
                        'danger' => static fn ($record): bool => $state === 'rejected', */
                    ])
                    ->default('■')
                    ->badge()
                    ->weight(FontWeight::ExtraBold),
                TextColumn::make('noms'),
                TextColumn::make('occupation.montant')
                    ->label('Loyer à payer'),
                TextColumn::make("loyers_sum_montant")
                    ->default(0)
                    ->label('Loyer payé'),
                    /* ->label('Montant payé'), */
            ])
            ->filters([
                // ...
                /* SelectFilter::make('locataire.loyers.annee')
                    ->options($ans), */
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
