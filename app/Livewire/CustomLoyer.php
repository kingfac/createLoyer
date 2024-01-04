<?php

namespace App\Livewire;

use DateTime;
use App\Models\Loyer;
use Livewire\Component;
use Filament\Forms\Form;
use App\Models\Locataire;

use Filament\Tables\Table;
use App\Actions\ResetStars;
use Livewire\Attributes\On;

use Livewire\WithPagination;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class CustomLoyer extends Component implements HasForms, HasTable
{

    
    use InteractsWithTable;
    use InteractsWithForms;

    public ?array $dataf = [];
    public $annee;
    public $mois;
    public $data = [];
    public $dt1;
    

    protected $listeners = ['actualiser1' => '$refresh'];

    public function render()
    {
        //dd(Loyer::whereRaw("DAY(created_at) = DAY(NOW())")->get()->sum('montant'));
        return view('livewire.custom-loyer');
    }

    #[On('close-modal')] 
    public function actualiser()
    {
        // ...
        $this->remplir();
        $this->dt1 = null;
        //$this->dispatch('actualiser1');
    }

    public function mount(): void
    {
        $this->form->fill();
        $this->remplir();
    }

    public function remplir(){
        //dd($this->form->getState()['annee']);
        $this->data = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id', 'LEFT OUTER')
        ->selectRaw('locataires.*, loyers.created_at')
        ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->form->getState()['mois'], $this->form->getState()['annee']])
        ->orderBy('locataires.id')
        ->get();

        //$this->data = $this->data->paginate(10);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id', 'LEFT OUTER')
            ->selectRaw('locataires.id, max(locataires.noms) as noms, max(loyers.created_at)')
            ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->form->getState()['mois'], $this->form->getState()['annee']])
            ->orderByRaw('locataires.id')
            ->groupByRaw('locataires.id'))

            ->columns([
                TextColumn::make('noms'),
                TextColumn::make('somme'),
                TextColumn::make('reste'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
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
                        ->inlineLabel()
                        ->required()
                        ->columnSpan(2),
                    TextInput::make('annee')
                        ->label('Année')
                        ->numeric()
                        ->maxValue(2030)
                        ->minValue(2023)
                        ->default($currentDate->format("Y"))
                        ->inlineLabel()
                        ->required()
                        ->columnSpan(3),
                    Actions::make([
                        Actions\Action::make('Afficher')
                        ->icon('heroicon-o-eye')
                        ->color('primary')
                        ->action(function () {
                            $this->dt1 = null;
                            $this->remplir();
                        })
                        ->outlined()
                        ->size(ActionSize::ExtraLarge),
                        Actions\Action::make('Evolution')
                        ->icon('heroicon-o-eye')
                        ->color('primary')
                        ->action(function () {
                            //dd($this->form->getState()['annee']);
                           $this->evolution();
                        })
                        ->size(ActionSize::ExtraLarge)
                    ])->columnSpan(2),
                    
                ])
                ->columns(7)

            ])
            
            ->statePath('dataf');
    }


    function detail($dt){

        $this->dt1 = $dt;
        $this->mois = $this->form->getState()['mois'];
        $this->annee = $this->form->getState()['annee'];
        
        //dd($dt);
        //$this->remplir();

        $this->dispatch('open-modal', id: 'detail');
    }

    public function evolution(){
        return response()->redirectTo('/loyers/'.$this->form->getState()['mois'].'/evolution');
    }

    public function imprimer($dt){
        
    }

}
