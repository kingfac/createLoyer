<?php

namespace App\Livewire;

use Livewire\Component;
use DateTime;
use App\Models\Loyer;
use Filament\Forms\Form;
use App\Models\Locataire;
use Filament\Widgets\Widget;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Support\Facades\Storage;

class CustomCreateLoyer extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $datas = [];

    public $locataire_id;
    public $data = [];
    public $locataire;
    public $annee;
    public $mois;

    public function render()
    {
        return view('livewire.custom-create-loyer');
    }

    public function mount(): void
    {
        $this->form->fill();
        //dd($this->dt);
        $this->locataire = Locataire::find($this->locataire_id);
        $this->remplir();
    }

    public function form(Form $form): Form
    {
        $currentDate = new DateTime();
        return $form
            ->schema([
                Section::make()->schema([                    
                    TextInput::make('montant')
                        ->required()
                        ->numeric()
                        ->label('Montant ($)')
                        ->inlineLabel()
                        ->default(0),
                        TextInput::make('observation')
                        ->label('Observation')
                        ->inlineLabel(),
                        
                    Toggle::make('garantie')
                        ->label('Utiliser la garantie'),
                    
                ])
                ->columns(2)

            ])
            ->statePath('datas');
    }

    public function create()
    {
        // dd($this->form->getState());
        if ($this->form->getState()['garantie']) {
            //dd(44);
            $montant = $this->form->getState()['montant'];
            //$locataire = Locataire::where('id',$this->form->getState()['locataire_id'])->first();
            $garantie = $this->locataire->garantie;

            //dd($montant,$this->locataire,$garantie);
            if(($garantie - $montant) < 0) {
                $this->dispatch('erreur-garantie');
            }          
            else{
                $this->locataire->update(['garantie' => $garantie-$montant]);
                //$this->form->fill();
                $this->dispatch('locataire-updated');
                return $this->store();
            }
        }
        else{
            return $this->store();
            
        }
    }

    private function store(){
        $loyer = Loyer::create([
            'montant' => $this->form->getState()['montant'],
            'mois' => $this->mois,
            'annee' => $this->annee,
            'locataire_id' => $this->locataire_id,
            'observation' => $this->form->getState()['observation'],
        ]);
        $this->form->fill();
        $this->dispatch('loyer-created');
        $this->remplir();
        return response()->streamDownload(function () use ($loyer) {
            echo Pdf::loadHtml(
                Blade::render('pdf', ['record' => $loyer])
            )->stream();
        }, $loyer->id.'1.pdf');
    }
    
    public function remplir(){
        //dd($this->locataire);
        $this->data = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id')
        ->selectRaw('locataires.*, loyers.montant, loyers.mois, loyers.annee, loyers.created_at as date_loyer, loyers.observation, loyers.garantie')
        ->where(['loyers.locataire_id' => $this->locataire_id, 'mois' => $this->mois, 'annee' => $this->annee])
        ->orderBy('locataires.id')
        ->get();

        //pdf
        $pdf = Pdf::loadHTML(Blade::render('situation', ['data' => $this->data, 'label' => 'SITUATION PERSONNE DU LOCATAIRE ', 'mois' => $this->mois, 'annee' => $this->annee, 'locataire' => $this->locataire]));
        //$pdf->save(public_path().'/pdf/doc.pdf');
        
        //$pdf = Pdf::loadHTML(Blade::render('evolution', ['data' => $this->data, 'label' => 'LOCATAIRE À JOUR DU MOIS DE '.$this->mois]));
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
    }

    public function imprimer($ly){
        dd($ly);
        
    }

    public function fermer(){
        
        $this->dispatch('close-modal', id: 'detail');
        //$this->dispatch('actualiser');
    }

    
}
