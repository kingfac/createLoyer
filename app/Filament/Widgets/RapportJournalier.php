<?php

namespace App\Filament\Widgets;

use DateTime;
use Filament\Tables;
use App\Models\Loyer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RapportJournalier extends BaseWidget
{

    public $mois;
    public $annee;
    public $lesMois = [
        '01' => 'Janvier',
        '02' => 'Février',
        '03' => 'Mars',
        '04' => 'Avril',
        '05' => 'Mais',
        '06' => 'Juin',
        '07' => 'Juillet',
        '08' => 'Aout',
        '09' => 'Septembre',
        '10' => 'Octobre',
        '11' => 'Novembre',
        '12' => 'Décembre'];
    

    public function table(Table $table): Table
    {
        $this->mois = new DateTime();
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y');
        $this->annee = $this->mois->format('Y');
        $this->mois = $this->lesMois[$this->mois->format('m')];
        
        return $table
            ->query(
                // ...
                Loyer::query()->whereRaw("DAY(created_at) = DAY(NOW())")
            )
            ->columns([
                // ...
                TextColumn::make('locataire.noms')->label('Locataire'),
                TextColumn::make('locataire.occupation.galerie.nom')->label('Galerie'),
                TextColumn::make('locataire.occupation.typeOccu.nom')->label('Occupation'),
                TextColumn::make('montant')->label('Loyer payé'),
                TextColumn::make('mois')->label('Mois'),
                TextColumn::make('created_at')->label('Heure')->time(),
            ]);
    }
}

