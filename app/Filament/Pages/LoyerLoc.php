<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;

use Filament\Forms\Concerns\InteractsWithForms;


class LoyerLoc extends Page implements HasForms
{

    use InteractsWithForms;
    //protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = 'Loyer';

    protected static string $view = 'filament.pages.loyer-loc';


}
