<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Garantie;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;

class GarantieController extends Controller
{
    public function downloadPdf($id)
    {
        // Trouve l'enregistrement de garantie par ID
        $garantie = Garantie::findOrFail($id);

        // Génère le PDF avec les données de garantie
        $pdf = Pdf::loadHtml(
            Blade::render('locataire_gar1', ['garantie' => $garantie])
        )->setPaper('a5', 'portrait');

        // Télécharge le PDF en tant que réponse
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'recu_garantie.pdf');
    }
}