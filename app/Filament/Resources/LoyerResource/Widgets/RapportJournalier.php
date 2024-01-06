<?php

namespace App\Filament\Resources\LoyerResource\Widgets;

use App\Models\Loyer;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RapportJournalier extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                // ...
                Loyer::query()
            )
            ->columns([
                // ...
            ]);
    }
}
