<?php

namespace App\Livewire;

use DateTime;
use Livewire\Component;
use Filament\Forms\Form;
use App\Models\Locataire;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Storage;
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
    public $data;

    protected $listeners = ['m4a' => '$refresh'];

    public function render()
    {
        $this->data = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id', 'LEFT OUTER')
        ->selectRaw('locataires.*')
        ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
        ->orderBy('locataires.id')
        ->get();
        $pdf = pdf::loadHTML(Blade::render('ev', ['data' => $this->data, 'label' => 'EVOLUTION DE PAIEMENT DU MOIS DE '.$this->mois]))->setPaper('a4', 'portrait');
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
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
            ->selectRaw('locataires.noms')
            ->withSum(
                [
                    'loyers' => fn($query) => $query->where(['mois'=>$this->mois, 'annee'=>$this->annee])
                ], 
                'montant'
            )
            ->groupBy(['locataires.id', 'locataires.noms'])
            )
            
            ->columns([
                TextColumn::make('S')
                    ->colors([
                        
                        'succTextColumness' => static fn ($record): bool => $record->occupation->montant == $record->loyers_sum_montant,
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

    public function imprimer(){

    }
}
