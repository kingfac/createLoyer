<?php

namespace App\Livewire;

use App\Exports\PartielPayExport;
use Livewire\Component;
use App\Models\Locataire;
use Livewire\Attributes\On;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LocPaiePartiel extends Component
{

    public $annee;
    public $mois;
    public $data;
    public $rows ;
    public $start_page = 1;
    public $total_page;
    public $perPage = 25;
    public $perPageOptions = [25, 50, 100]; // Options for per page selection
    public $htmlContent;

    protected $listeners = ['m2a' => '$refresh'];

    public function render()
    {

        $this->data = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id')
        ->selectRaw('locataires.*')
        ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
        ->orderBy('locataires.id')
        ->skip(($this->start_page - 1) * $this->perPage)//
        ->take($this->perPage)//
        ->get();
        $this->rows = $this->data->count();
        $this->total_page = ceil($this->rows/$this->perPage);
        // $pdf = Pdf::loadHTML(Blade::render('partiel', ['data' => $this->data, 'label' => 'Locataires avec paiements partiels du mois de '.$this->mois, 'inverse' =>true]))->setPaper('a4', 'landscape');
        // Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
        return view('livewire.loc-paie-partiel');
    }
    public function mount(){
        Excel::store(new PartielPayExport($this->mois, $this->annee), 'public/etat/partial.xlsx');
        $filePath = public_path('storage/etat/partial.xlsx');

        // Load the Excel file using PHPSpreadsheet
        $spreadsheet = IOFactory::load($filePath);

        // Convert the first sheet's data to HTML for display
        $writer = IOFactory::createWriter($spreadsheet, 'Html');
        ob_start();
        $writer->save('php://output');
        $htmlOutput = ob_get_clean();
        preg_match('/<table.*?<\/table>/s', $htmlOutput, $matches);
        $this->htmlContent = $matches[0] ?? 'No data found';
    }

    #[On('m2')]
    public function update($mois, $annee)
    {
        // ...
        $this->annee = $annee;
        $this->mois = $mois;
        $this->dispatch('m2a');
    }

    public function gotoPage($page)
    {
        $this->start_page = max(1, min($page, ceil($this->rows / $this->perPage)));
    }

    public function exportExcel()
    {
        return Excel::download(new PartielPayExport($this->mois, $this->annee), 'PartielPayExport.xlsx');
    }
}
