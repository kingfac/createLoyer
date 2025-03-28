<?php

namespace App\Exports;

use App\Models\Locataire;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PartielPayExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithColumnFormatting, WithStyles
{
    public $mois;
    public $annee;

    private $totalLoyerMensuel = 0;
    private $totalLoyerPaye = 0;
    private $rows = 0;
    private $totalReste = 0;

    public function __construct($mois, $annee)
    {
        $this->mois = $mois;
        $this->annee = $annee;
    }

     /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $locataires = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id', 'left outer')
        ->selectRaw('
            locataires.id,
            locataires.noms,
            locataires.occupation_id
        ')
        ->selectRaw("(select sum(`loyers`.`montant`) from `loyers` where `locataires`.`id` = `loyers`.`locataire_id` and (`mois` = ? and `annee` = ?)) as `somme`", [$this->mois, $this->annee])
        ->groupBy('locataires.id', 'locataires.noms', 'locataires.occupation_id')
        ->orderBy('locataires.id')
        ->get()
        ->filter(function($dt){
            return $dt->somme < $dt->occupation->montant && $dt->somme > 0;
        });

        // Calculate Totals
        foreach ($locataires as $locataire) {
            $loyerMensuel = $locataire->occupation->montant ?? 0;
            $loyerPaye = $locataire->somme ?? 0;
            $reste = $loyerMensuel - $loyerPaye;

            $this->totalLoyerMensuel += $loyerMensuel;
            $this->totalLoyerPaye += $loyerPaye;
            $this->totalReste += $reste;
        }

        $this->rows = $locataires->count();
        //dd($locataire);
        return $locataires;
    }

    public function headings(): array
    {
        return [
            'N°',
            'Locataire',
            'Galerie',
            'Type Occupation',
            'Loyer Mensuel ($)',
            'Loyer Payé ($)',
            'Reste ($)'
        ];
    }

    public function map($locataire): array
    {
        $loyerMensuel = $locataire->occupation->montant ?? 0;
        $loyerPaye = $locataire->somme ?? 0;
        $reste = $loyerMensuel - $loyerPaye;

        return [
            $locataire->id,
            $locataire->noms,
            optional($locataire->occupation->galerie)->nom ?? 'N/A',
            optional($locataire->occupation->typeOccu)->nom ?? 'N/A',
            $loyerMensuel,
            $loyerPaye,
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
                $event->sheet->setCellValue('E' . $lastRow, $this->totalLoyerMensuel);
                $event->sheet->setCellValue('F' . $lastRow, $this->totalLoyerPaye);
                $event->sheet->setCellValue('G' . $lastRow, $this->totalReste);

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
