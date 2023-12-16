<?php

namespace App\Filament\Resources\DettesResource\Widgets;

use App\Models\Locataire;
use App\Models\Loyer;
use App\Models\Occupation;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

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
        return $form
            ->schema([
                TextInput::make('mois')
                    ->required(),
                TextInput::make('annee')
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
        $d = Loyer::where('mois',$this->form->getState()['mois'])->where('annee',$this->form->getState()['annee'])->get();
                        
        $n=0;
        $dettes = [];
        $locs = Locataire::all();


        // dd($locs[0]['nom']);

        for ($i=0; $i <= $locs->count(); $i++) { 

            $l = Loyer::where('mois',$this->form->getState()['mois'])->where('locataire_id',$locs[$i]['id'])->get();
            // $occ = Occupation::where('id',$l[0]['occupation_id'])->get();
            $m = $l[0]->locataire->occupation->montant;
            
            if ($l->count() > 1) {
                        # code...
                // dd('plusieurs tranches');
                $somme = $l->sum('montant');
                if($somme == $m){
                    dd('pas de dette');
                }
                    
            }
            
            elseif ($l->count() == 1) {
                $somme = $l['montant'];

                if ($somme == $m) {
                    dd('pas de dette');
                }
            }
            // $dettes[$i] = []
        }
   

    
                        
    }
}


