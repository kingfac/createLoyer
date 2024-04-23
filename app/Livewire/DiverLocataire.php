<?php

namespace App\Livewire;

use App\Models\Divers;
use Livewire\Component;
use App\Models\Locataire;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;

class DiverLocataire extends Component
{
    public $data;
    public $divers;
    public $locataires;
    public $locataire_id;
    public function render()
    {
        $this->data = Locataire::where('actif', true)->get();
        $this->divers = Divers::where('locataire_id', $this->locataire_id)->get();
        $pdf = Pdf::loadHTML(Blade::render('divers', ['data' => $this->data]));
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
        
        return view('livewire.diver-locataire');
    }

    public function remplir(){
        $this->data = Divers::where('locataire_id', $this->locataire_id)->get();
        $pdf = Pdf::loadHTML(Blade::render('divers', ['data' => $this->data]));
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
    }
}
