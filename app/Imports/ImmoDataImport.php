<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImmoDataImport implements WithMultipleSheets
{
    public $totalImported = 0;

    public function sheets(): array
    {
        return [
            'APPARTEMENT' => new \App\Imports\SingleSheetImport($this),
            'DEPOTS' => new \App\Imports\SingleSheetImport($this),
            'MAGASINS' => new \App\Imports\SingleSheetImport($this),
            'MESA-ETAL-KSQ' => new \App\Imports\SingleSheetImport($this),
        ];
    }
}
