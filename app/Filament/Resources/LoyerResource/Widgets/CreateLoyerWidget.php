<?php

namespace App\Filament\Resources\LoyerResource\Widgets;

use DateTime;
use App\Models\Loyer;
use Filament\Forms\Form;
use App\Models\Locataire;
use Filament\Widgets\Widget;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class CreateLoyerWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.resources.loyer-resource.widgets.create-loyer-widget';

    protected int | string | array $columnSpace = 'full';
    protected int | string | array $columnSpan = [
        'md' => 4,
        '2xl' => 2,
    ];

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        $currentDate = new DateTime();
        return $form
            ->schema([
                Section::make()->schema([

                    Select::make('locataire_id')
                        ->label('Locataire')
                        ->options(Locataire::where('actif',true)->pluck('noms','id'))
                        ->searchable()
                        ->validationMessages(['required' => 'Veuillez remplir ce champ'])
                        ->preload()
                        ->required(),
                    Select::make('mois')
                        ->options(['Janv
                        ier' => 'Janvier','Février' => 'Février','Mars' => 'Mars','Avril' => 'Avril','Mais' => 'Mais','Juin' => 'Juin','Juillet' => 'Juillet','Aout' => 'Aout','Septembre' => 'Septembre','Octobre' => 'Octobre','Novembre' => 'Novembre','Décembre' => 'Décembre'])
                        ->label("Mois")
                        ->required(),
                    TextInput::make('annee')
                        ->label('Année')
                        ->numeric()
                        ->maxValue(2030)
                        ->minValue(2023)
                        ->default($currentDate->format("Y"))
                        ->required(),
                    
                    TextInput::make('montant')
                        ->required()
                        ->numeric()
                        ->label('Montant ($)')
                        ->default(0),
                        
                    Toggle::make('garantie')
                        ->label('Utiliser la garantie'),
                ])
                ->columns(4)

            ])
            ->statePath('data');
    }

    public function create(): void
    {
        // dd($this->form->getState());
        if ($this->form->getState()['garantie']) {
            $montant = $this->form->getState()['montant'];
            $locataire = Locataire::where('id',$this->form->getState()['locataire_id'])->first();
            $garantie = $locataire->garantie;

            // dd($montant,$locataire,$garantie);
            if(($garantie - $montant) < 0) {
                $this->dispatch('erreur-garantie');
            }          
            else{
                $locataire->update(['garantie' => $garantie-$montant]);
                //$this->form->fill();
                $this->dispatch('locataire-updated');
                $this->store();
            }
        }
        else{
            $this->store();
            
        }
    }

    private function store(){
        Loyer::create($this->form->getState());
            $this->form->fill();
            $this->dispatch('loyer-created');
    }

    public function total(){
        $loyer = Loyer::where('locataire_id',$this->form->getState()['locataire_id'])
                        ->where('annee',$this->form->getState()['annee'])
                        ->where('mois',$this->form->getState()['mois'])
                        ->get();
        //dd($loyer);
        /* echo Pdf::loadHtml(
            Blade::render('factureTotal.blade', ['record' => $loyer])
        )->stream(); */

        return response()->streamDownload(function () use ($loyer) {
            echo Pdf::loadHtml(
                Blade::render('factureTotal', ['record' => $loyer])
            )->stream();
        }, $this->form->getState()['mois'].'_'.$this->form->getState()['annee'].'_loyerTotal.pdf');
    }
}
