<?php

namespace App\Livewire;

use App\Models\Loyer;
use App\Models\Divers;
use App\Models\Depense;
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
    public $recu;
    public $prevFinale;
    public $entreeBrut;



    protected $listeners = ['m0a' => '$refresh'];


    public function render()
    {   $this->prevFinale = Locataire::all()->where('actif', true)->sum('occupation.montant');
        $this->revFinale();
        $this->entreeBrut();
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
        $date = now()->format('Y-m-d');
        $this->data1 = Divers::where('entreprise',true)->whereRaw("date(created_at) = '$date' ")->get();
        $this->data2 = Divers::where('entreprise',false)->whereRaw("date(created_at) = '$date' ")->get();

        // $pdf = Pdf::loadHTML(Blade::render('evolution', ['data' => $this->data, 'label' => 'LOCATAIRE À JOUR DU MOIS DE '.$this->mois]));
        // Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
    }

    public function revFinale(){
        
        $prevu = Locataire::all()->where('actif', true)->sum('occupation.montant');


        $this->recu = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id')
        ->selectRaw('locataires.*, loyers.created_at as dl')
        ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
        ->orderByRaw('locataires.id, loyers.created_at desc')
        ->get();
        $somme = 0;
        $_id = 0;
        // dd($this->recu);

        foreach ($this->recu as $val) {
            # code...
            if($_id != $val->id){
                $somme += $val->somme;
                $_id = $val->id;
            }
        }
        
        $this->recu = $somme;
        // dd($recu);

    }

    public function entreeBrut()
    {
        $date = now()->format('Y-m-d');

        $this->entreeBrut = Loyer::whereRaw(" date(created_at) = '$date' ")->sum('montant');
    }
    




}
