<?php

namespace App\Filament\Resources\DettesResource\Widgets;

use DateTime;
use App\Models\Loyer;
use Filament\Forms\Form;
use App\Models\Locataire;
use App\Models\Occupation;
use Filament\Tables\Table;
use Filament\Widgets\Widget;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Columns\TextColumn;

class DetteWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.resources.dettes-resource.widgets.dette-widget';
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
                Select::make('mois')->options(['Janvier' => 'Janvier','Février' => 'Février','Mars' => 'Mars','Avril' => 'Avril','Mais' => 'Mais','Juin' => 'Juin','Juillet' => 'Juillet','Aout' => 'Aout','Septembre' => 'Septembre','Octobre' => 'Octobre','Novembre' => 'Novembre','Décembre' => 'Décembre'])
                        ->required(),
                TextInput::make('annee')
                    ->label('Année')
                    ->numeric()
                    ->maxValue(2030)
                    ->minValue(2023)
                    ->default($currentDate->format("Y"))
                    ->required(),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        Loyer::create($this->form->getState());
        $this->form->fill();
        $this->dispatch('contact-created');
    }

    public function dette()
    {
        return response()->streamDownload(function ()  {
            echo Pdf::loadHtml(
                Blade::render('dettes', ['mois'=>$this->form->getState()['mois'],'annee'=>$this->form->getState()['annee']])
            )->stream();
        }, $this->form->getState()['mois'].'_'.$this->form->getState()['annee'].'_dettes.pdf');
                                
    }



}


