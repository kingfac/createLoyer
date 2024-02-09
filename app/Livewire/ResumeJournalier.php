<?php

namespace App\Livewire;

use App\Models\Depense;
use App\Models\Divers;
use Livewire\Component;
use App\Models\Locataire;
use Livewire\Attributes\On;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;

class ResumeJournalier extends Component
{
    public $annee;
    public $mois;
    public $data;
    public $data1;
    public $data2;



    protected $listeners = ['m0a' => '$refresh'];
    public function render()
    {
        return view('livewire.resume-journalier');
    }

    public function mount(){
        $this->remplir();
    }

    #[On('m0')] 
    public function update($mois, $annee)
    {
        // ...
        $this->annee = $annee;
        $this->mois = $mois;
        $this->dispatch('m0a');
        $this->remplir();
        
    }

    public function remplir(){

        $this->data = Depense::all();
        $this->data1 = Divers::where('entreprise',true)->get();
        $this->data2 = Divers::where('entreprise',false)->get();

        // $pdf = Pdf::loadHTML(Blade::render('evolution', ['data' => $this->data, 'label' => 'LOCATAIRE À JOUR DU MOIS DE '.$this->mois]));
        // Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
    }


}
