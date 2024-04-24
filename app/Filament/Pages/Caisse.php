<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class Caisse extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Caisse';
    protected static string $view = 'filament.pages.caisse';

    public $menu;
    public $menus = [
        'grande caisse',
        'petite caisse'
    ];

    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('date'),
        ])
        ->columns(4)
        ->statePath('data');
    }

    public function go($menu){
        //dd($menu);
        // $this->mois = $this->form->getState()['mois'];
        // $this->annee = $this->form->getState()['annee'];
        if($this->menu != $this->menus[$menu]){
            $this->menu = $this->menus[$menu];
        }
        //$this->emit('evolution', ['mois' => $this->form->getState()['mois'], 'annee' => $this->form->getState()['annee']]);
        // $this->dispatch('m'.$menu, mois: $this->form->getState()['mois'], annee: $this->form->getState()['annee']);
    }

}
