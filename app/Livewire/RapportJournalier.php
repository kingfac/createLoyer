<?php

namespace App\Livewire;

use App\Models\Loyer;
use App\Models\Galerie;
use Livewire\Component;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\Locataire;

class RapportJournalier extends Component 
{
    
    public $data;
    public $dataGroupe;
    public $mois;
    public $annee;
    public $lesMois = [
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

    public $Mois2 = [
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

    protected $listeners = ['m13a' => '$refresh'];
    public function render()
    {
        return view('livewire.rapport-journalier');
    }
    public function mount(){
        $this->remplir();
    }
    #[On('m13')]
    public function update($mois, $annee)
    {
    
        $this->annee = $annee;
        $this->mois = $mois;
        $this->dispatch('m0a');
        $this->remplir();
        
    }
    public function remplir(){
        // $this->data = Loyer::whereRaw("DATE(created_at)=?",now()->format('Y-m-d'))->get();
        $this->data = Loyer::whereDate('created_at', now())
                            ->orderBy('locataire_id')
                            ->get();
     
    }


}
