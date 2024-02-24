<?php

namespace App\Filament\Pages;

use DateTime;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\Locataire;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class EtatsEnSortie extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.etats-en-sortie';
    protected static ?string $title = 'Etats en sortie';
    protected static ?int $navigationSort = 7;


    public ?array $data = [];

    public $liste;

    public $mois;
    public $annee;
    public $menu;

    public $menus = [
        'Locataires à jour', 
        'Locataires avec payement en retard', 
        'Locataires avec payement partiel',
        'Locataires avec soldes impayés',
        'Evolution Loyer/Locataire',
        'Situation des paiements Global',
        'Paiement journalier',
        'Total garantie',
        'Liste locataire avec arrieres',
        'Liste prevision mensuel',
        'Rapport synthese',
        'Rapport mensuel',
        'Sorties avec dettes',
        'Rapport journalier',
        'Résumé Journalier'
    ];

    public function mount(): void
    {
        $this->menu;
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        $currentDate = new DateTime();
        return $form
            ->schema([
                Section::make()->schema([

                    Select::make('mois')
                        ->options(['Janvier' => 'Janvier','Février' => 'Février','Mars' => 'Mars','Avril' => 'Avril','Mais' => 'Mais','Juin' => 'Juin','Juillet' => 'Juillet','Aout' => 'Aout','Septembre' => 'Septembre','Octobre' => 'Octobre','Novembre' => 'Novembre','Décembre' => 'Décembre'])
                        ->label("Mois")
                        ->default('Janvier')
                        ->required(),
                    TextInput::make('annee')
                        ->label('Année')
                        ->numeric()
                        ->maxValue(2030)
                        ->minValue(2023)
                        ->default($currentDate->format("Y"))
                        ->required(),
                   /*  Radio::make('type')
                        ->options([
                            'Mois' => 'Mois',
                            'Annee' => 'Annee',
                        ])
                        ->inline()
                        ->label('Type')
                        ->inlineLabel(false)
                        ->required() */
                ])
                ->columns(4)

            ])
            ->statePath('data');
    }

    public function loca(){
        dd($this->form->getState()['type']);
    }
    public function go($menu){
        //dd($menu);
        $this->mois = $this->form->getState()['mois'];
        $this->annee = $this->form->getState()['annee'];
        if($this->menu != $this->menus[$menu]){
            $this->menu = $this->menus[$menu];
        }
        //$this->emit('evolution', ['mois' => $this->form->getState()['mois'], 'annee' => $this->form->getState()['annee']]);
        $this->dispatch('m'.$menu, mois: $this->form->getState()['mois'], annee: $this->form->getState()['annee']);
    }

    // public static function getNavigationBadge(): ?string
    // {
    //     return 5;   
    // }

}
