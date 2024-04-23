<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\Locataire;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;

class TotalGarantie extends Component
{

    public $data;

    public function render()
    {
        $this->data = Locataire::where('actif', true)->get();
        $pdf = Pdf::loadHTML(Blade::render('totaldivers', ['data' => $this->data]));
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
        return view('livewire.total-garantie');
    }

    
}
