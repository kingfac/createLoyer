<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Locataire;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class LocLoyerTotal extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    use ExposesTableToWidgets;

    public $annee;
    public $mois;
    public $data;
    public $prevu; 
    public $recu;
    public $_id;

    protected $listeners = ['m5a' => '$refresh'];

    #[On('m5')] 
    public function render()
    {

        $this->data = Locataire::where('actif', true)->join('loyers', 'loyers.locataire_id', '=', 'locataires.id')
        ->selectRaw('locataires.*')
        ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
        ->orderBy('locataires.id')
        ->get();

        $this->prevu = Locataire::all()->where('actif', true)->sum('occupation.montant');

        // $this->prevu = Locataire::where('actif', true)->sum('occupation.montant');

        $this->recu = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id', 'LEFT OUTER')
        ->selectRaw('locataires.*, loyers.created_at')
        ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
        ->orderBy('locataires.id')
        ->get();

        $somme = 0;
        foreach ($this->recu as $val) {
            # code...
            if($this->_id != $val->id){
                $somme += $val->somme;
                $this->_id = $val->id;
            }
        }
        
        $this->recu = $somme;
        
        return view('livewire.loc-loyer-total');
    }


    #[On('m5')] 
    public function update($mois, $annee)
    {
        // ...
        $this->annee = $annee;
        $this->mois = $mois;
        // $this->remplir();
        $this->dispatch('m5a');
    }

    // public function remplir(){
    //     $this->data = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id')
    //     ->selectRaw('locataires.*')
    //     ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
    //     ->orderBy('locataires.id')
    //     ->get();

    //     $this->prevu = Locataire::all()->sum('occupation.montant');
    //     $this->recu = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id', 'LEFT OUTER')
    //     ->selectRaw('locataires.*, loyers.created_at')
    //     ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
    //     ->orderBy('locataires.id')
    //     ->get();

    //     $somme = 0;
    //     foreach ($this->recu as $val) {
    //         # code...
    //         if($this->_id != $val->id){
    //             $somme += $val->somme;
    //             $this->_id = $val->id;
    //         }
    //     }
        
    //     $this->recu = $somme;
    // }
    
    public function table(Table $table): Table
    {
        $ans = [];
        for ($i = 2009; $i <= 2030; $i++) {
            $ans[$i] = $i;
        }
        return $table
            ->query(Locataire::query()->where('actif', true)
            //  ->select(['locataires.*', 'loyers.montant', 'loyers.mois'])
            // ->join('loyers', 'loyers.locataire_id', '=', 'locataires.id', 'LEFT OUTER') */
            ->join('loyers', 'loyers.locataire_id', '=', 'locataires.id')
            ->withSum(
                [
                    'loyers' => fn($query) => $query->where(['mois'=>$this->mois, 'annee'=>$this->annee])
                ], 
                'montant'
            )
            ->groupBy(['locataires.id', 'locataires.nom', 'locataires.postnom', 'locataires.prenom'])

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


