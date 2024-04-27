<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Locataire;
use Livewire\Attributes\On;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;

class LocPaiePartiel extends Component
{

    public $annee;
    public $mois;
    public $data;

    protected $listeners = ['m2a' => '$refresh'];

    public function render()
    {
        $this->data = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id')
        ->selectRaw('locataires.*')
        ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
        ->orderBy('locataires.id')
        ->get();
        $pdf = Pdf::loadHTML(Blade::render('partiel', ['data' => $this->data, 'label' => 'Locataires avec paiements partiels du mois de '.$this->mois, 'inverse' =>true]))->setPaper('a4', 'landscape');
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
        return view('livewire.loc-paie-partiel');
    }

    #[On('m2')] 
    public function update($mois, $annee)
    {
        // ...
        $this->annee = $annee;
        $this->mois = $mois;
        $this->dispatch('m2a');
    }
}
