<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\Locataire;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Exports\TotalGarantiExport;

class TotalGarantie extends Component
{

    public $data;
    public $htmlContent;

    public function render()
    {
        $this->data = Locataire::where('actif', true)->get();
        // $pdf = Pdf::loadHTML(Blade::render('totalgarantie', ['data' => $this->data]))->setPaper('a4', 'landscape');
        // Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
        return view('livewire.total-garantie');
    }

    public function mount(){
        Excel::store(new TotalGarantiExport(), 'public/etat/garantie.xlsx');
        $filePath = public_path('storage/etat/garantie.xlsx');

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


}
