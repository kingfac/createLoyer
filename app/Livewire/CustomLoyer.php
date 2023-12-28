<?php

namespace App\Livewire;

use DateTime;
use Livewire\Component;
use Filament\Forms\Form;
use App\Models\Locataire;
use App\Actions\ResetStars;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;

use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\ActionSize;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;


class CustomLoyer extends Component implements HasForms
{

    use InteractsWithForms;

    public ?array $dataf = [];
    public $annee;
    public $mois;
    public $data = [];

    

    public function render()
    {
        return view('livewire.custom-loyer');
    }

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
                    Actions::make([
                        Actions\Action::make('Afficher')
                        ->icon('heroicon-o-eye')
                        ->color('primary')
                        ->action(function () {
                            //dd($this->form->getState()['annee']);
                            $this->data = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id', 'LEFT OUTER')
                            ->selectRaw('locataires.*')
                            ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->form->getState()['mois'], $this->form->getState()['annee']])
                            ->orderBy('locataires.id')
                            ->get();
                        })
                        ->size(ActionSize::ExtraLarge)
                    ]),
                    
                ])
                ->columns(4)

            ])
            
            ->statePath('dataf');
    }

}
