<?php

namespace App\Exports;

use App\Models\Locataire;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TotalGarantiExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithColumnFormatting, WithStyles
{
    private $rows = 0;
    private $paye = 0;
    private $utilise = 0;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $locataires = Locataire::where('actif', true)->get();;
        $this->rows = $locataires->count();
        return $locataires;
    }

    public function headings(): array
    {
        return [
            'N°',
            'Locataire',
            'Galerie',
            'Occupation',
            'Garantie payée ($)',
            'Garantie utilisée ($)',
            'Reste ($)'
        ];
    }

    public function map($locataire): array
    {
        $paye = 0;
        foreach ($locataire->garanties as $gar) {
            # code...
            if($gar->restitution == false){
                $paye += $gar->montant;
            }
        }
        $utilise = 0;

        foreach ($locataire->loyers as $loyer) {
            if($loyer->garantie){
                $utilise += $loyer->montant;
            }
        }
        $this->utilise+=$utilise;
        $this->paye+=$paye;

        $reste = $paye - $utilise;

        return [
            $locataire->id,
            $locataire->noms,
            optional($locataire->occupation->galerie)->nom ?? 'N/A',
            optional($locataire->occupation->typeOccu)->nom ?? 'N/A',
            $paye,
            $utilise,
            $reste
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $lastRow = $event->sheet->getHighestRow() + 1;

                // Add totals at the bottom
                $event->sheet->setCellValue('D' . $lastRow, 'Totaux:');
                $event->sheet->setCellValue('E' . $lastRow, $this->paye);
                $event->sheet->setCellValue('F' . $lastRow, $this->utilise);
                $event->sheet->setCellValue('G' . $lastRow, $this->paye - $this->utilise);

                // Styling for the total row
                $event->sheet->getStyle("D{$lastRow}:G{$lastRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => 'center'],
                    'borders' => [
                        'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ]
                ]);
            },
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => '[$$-409]#,##0.00', // ✅ USD Format
            'F' => '[$$-409]#,##0.00', // ✅ USD Format
            'G' => '[$$-409]#,##0.00', // ✅ USD Format
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // **Heading Row Styles**
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],

            // **Body Rows Style**
            'A2:G'.($this->rows + 1) => [ // Adjust range as needed
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]
        ];
    }
}
