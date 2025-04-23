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
    public $locataires;
    public $locataire_id;


    public function render()
    {
        $this->data = Locataire::join('divers', 'divers.locataire_id', '=', 'locataires.id')
                ->selectRaw('
                    locataires.id,
                    locataires.noms,
                    locataires.occupation_id
                ')
                ->where('actif', true)
                ->groupBy('locataires.id', 'locataires.noms', 'locataires.occupation_id')
                ->orderBy('locataires.id')
                ->get();
        $pdf = Pdf::loadHTML(Blade::render('totaldivers', ['data' => $this->data, 'label' => 'Locataires avec paiements partiels du mois de ', 'inverse' =>true]))->setPaper('a4', 'landscape');
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());

        return view('livewire.diver-locataire');
    }

    public function remplir(){
        $this->data = Divers::where('locataire_id', $this->locataire_id)->get();
        $pdf = Pdf::loadHTML(Blade::render('divers', ['data' => $this->data]))->setPaper('a4', 'landscape');
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
    }
}
