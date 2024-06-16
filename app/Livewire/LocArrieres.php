<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Locataire;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;

class LocArrieres extends Component
{
    public $locataires;

    public function render()
    {
        $this->remplir();
        $pdf = Pdf::loadHTML(Blade::render('arrieres', ['locataires' =>  $this->locataires,'label' => 'Arriérés des locataires']))->setPaper('a4', 'portrait');
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
        return view('livewire.loc-arrieres');
    }

    public function remplir(){
        $this->locataires = Locataire::all();
    }

    public function imprimer(){
        $pdf = Pdf::loadHTML(Blade::render('arrieres', ['locataires' =>  $this->locataires,'label' => 'Arriérés des locataires']))->setPaper('a4', 'landscape');
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
        // return response()->download('../public/storage/pdf/doc.pdf');
    }
}
