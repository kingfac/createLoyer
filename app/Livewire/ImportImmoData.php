<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Imports\ImmoDataImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportImmoData extends Component
{

    use WithFileUploads;

    public $file;
    public $importedCount = 0;


    public function import()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);
        // On passe le composant à l'import pour stocker le nombre
        $importer = new ImmoDataImport();
        Excel::import($importer, $this->file->getRealPath());

        $this->importedCount = $importer->totalImported;

        session()->flash('message', "Importation réussie : {$this->importedCount} locataires importés.");
    }

    public function render()
    {
        return view('livewire.import-immo-data');
    }

}


