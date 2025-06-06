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

class CustomLoyer extends Component implements HasForms
{


    use InteractsWithForms;

    public ?array $dataf = [];
    public $annee;
    public $mois;
    public $data = [];
    public $dt1;

    public $recherche;

    public $selectedGal;
    public $selectedOccu = '';
    public $rows ;
    public $start_page = 1;
    public $total_page;
    public $perPage = 15;
    public $perPageOptions = [15, 25, 50, 100]; // Options for per page selection
    public $offset;



    protected $listeners = ['actualiser1' => '$refresh'];

    public function render()
    {
        //dd(Loyer::whereRaw("DAY(created_at) = DAY(NOW())")->get()->sum('montant'));
        $this->rows = Locataire::where('actif', true)->count();
        $this->total_page = ceil($this->rows/$this->perPage);
        $this->remplir($this->recherche, $this->selectedGal);


        //dd($this->offset, $this->perPage, $this->total_page);
        return view('livewire.custom-loyer');
    }

    #[On('close-modal')]
    public function actualiser()
    {
        // ...
        //$this->remplir();
        $this->dt1 = null;
        $this->mois = $this->form->getState()['mois'];
        $this->annee = $this->form->getState()['annee'];
        //$this->dispatch('actualiser1');
    }

    public function updatedPerPage()
    {
        $this->start_page = 1; // Reset to first page when perPage changes
        //$this->remplir($this->recherche, $this->selectedGal);
    }

    public function clear(){
        $this->recherche = '';
    }

    public function mount(): void
    {
        $this->form->fill();
        $this->mois = $this->form->getState()['mois'];
        $this->annee = $this->form->getState()['annee'];

        //$this->remplir();
    }

    public function remplir($recherche = '', $gal = ''){
        //dd($this->form->getState()['annee']);
        /* if($recherche == null){
            $this->data = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id', 'LEFT OUTER')
            ->selectRaw('locataires.*, loyers.created_at')
            ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->form->getState()['mois'], $this->form->getState()['annee']])
            ->orderBy('locataires.id')
            ->get();
        }
        else{

        } */

        $this->data = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id', 'LEFT OUTER')
            //->join('occupations', 'loyers.locataire_id', '=', 'locataires.id', 'LEFT OUTER')
            ->selectRaw('locataires.*, loyers.created_at')
            ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->form->getState()['mois'], $this->form->getState()['annee']])
            ->orderBy('locataires.id')
            ->orderBy('somme', 'ASC')
            ->where('noms', 'like', '%' . $recherche . '%')
            ->Orwhere('matricule', 'like','%' . $recherche . '%')
            //->orWhere('noms', 'like', '%' . $gal . '%')
            ->skip(($this->start_page - 1) * $this->perPage)//
            ->take($this->perPage)//
            // // ->offset(0)
            // ->limit(5)
            ->get();

        //$this->data = $this->data->paginate(10);
    }
    public function gotoPage($page)
    {
        $this->start_page = max(1, min($page, ceil($this->rows / $this->perPage)));
    }

  /*   public function table(Table $table): Table
    {
        return $table
            ->query(Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id', 'LEFT OUTER')
            ->selectRaw('locataires.id, max(locataires.noms) as noms, max(loyers.created_at) as ld')
            ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->form->getState()['mois'], $this->form->getState()['annee']])
            ->orderByRaw('locataires.id')
            ->groupByRaw('locataires.id'))

            ->columns([
                TextColumn::make('noms')->searchable(),
                TextColumn::make('somme')->default(0)->money()->label('Loyer mensuel'),
                TextColumn::make('ld'),
            ])
            ->searchable()
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
 */
    public function form(Form $form): Form
    {
        $currentDate = new DateTime();
        return $form
            ->schema([
                Section::make()->schema([


                    Select::make('mois')
                        ->options(['Janvier' => 'Janvier','Février' => 'Février','Mars' => 'Mars','Avril' => 'Avril','Mais' => 'Mai','Juin' => 'Juin','Juillet' => 'Juillet','Aout' => 'Aout','Septembre' => 'Septembre','Octobre' => 'Octobre','Novembre' => 'Novembre','Décembre' => 'Décembre'])
                        ->label("Mois")
                        ->default(function(){
                            $Mois1 = [
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
                                '12' => 'Décembre'
                            ];
                            $Mois2 = [
                                'Janvier' => '01',
                                'Février' => '02',
                                'Mars' => '03',
                                'Avril' => '04',
                                'Mais' => '05',
                                'Juin' => '06',
                                'Juillet' => '07',
                                'Aout' => '08',
                                'Septembre' => '09',
                                'Octobre' => '10',
                                'Novembre' => '11',
                                'Décembre' => '12'
                            ];

                            $date = NOW();
                            $mois =$date->format('m');

                            return $Mois1[$mois];
                        })
                        ->inlineLabel()
                        ->required()
                        ->columnSpan(2),
                    Select::make('annee')
                        ->label('Année')
                        ->default($currentDate->format("Y"))
                        ->options(function(){

                            return [
                                '2023' => 2023,
                                '2024' => 2024,
                                '2025' => 2025,
                                '2026' => 2026,
                                '2027' => 2027,
                                '2028' => 2028,
                                '2029' => 2029,
                                '2030' => 2030,
                            ];
                        })
                        ->inlineLabel()
                        ->required()
                        ->columnSpan(3),
                    Actions::make([
                        Actions\Action::make('Afficher')
                        ->icon('heroicon-o-eye')
                        ->color('primary')
                        ->action(function () {
                            $this->dt1 = null;
                            //$this->form->fill();
                            //$this->resetTable();
                            $this->remplir();
                        })

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
        return response()->redirectTo('loyers/'.$this->form->getState()['mois'].'/'.$this->form->getState()['annee'].'/evolution');
    }

    public function imprimer($dt){
        $loyer = Loyer::where('locataire_id')->get();
        dd($loyer);
    }

}
