<?php

namespace App\Imports;

use App\Models\Galerie;
use App\Models\TypeOccu;
use App\Models\Occupation;
use App\Models\Locataire;
use App\Models\Loyer;
use App\Models\Garantie;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SingleSheetImport implements ToCollection
{
    protected $mainImport;

    public function __construct($mainImport)
    {
        $this->mainImport = $mainImport;
    }

    public function collection(Collection $rows)
    {
        $currentMonth = 5;
        $currentYear = 2025;

        foreach ($rows as $index => $row) {
            if ($index < 1 || empty($row[0])) continue;
            //if (empty(trim($row[0])) || strtolower(trim($row[0])) === 'nom et postnom' || strtolower(trim($row[0])) === 'noms & postnom') continue;


            $nomComplet = trim($row[0]);
            $galerieNom = strtolower(trim($row[1]));
            $typeOccuNom = strtolower(trim($row[2])); // récupéré depuis la colonne
            $ref = trim($row[3]);
            $numOccu = trim($row[4]);

            $garantieMontant = floatval($row[5]);
            $loyerMontant = floatval($row[6]);
            $dettes = floatval($row[7]);
            $moisDettes = intval($row[8]);

            // Estimation du mois de début
            // $moisDebut = $currentMonth - $moisDettes;
            // $anneeDebut = $currentYear;
            // while ($moisDebut < 1) {
            //     $moisDebut += 12;
            //     $anneeDebut -= 1;
            // }
            // if ($moisDettes == 0) {
            //     $moisDebut = 1;
            //     $anneeDebut = 2025;
            // }
            // Récupération des colonnes du fichier Excel
            $moisDebut = (intval($row[9]) >= 1 && intval($row[9]) <= 12) ? intval($row[9]) : 1;
            $anneeDebut = intval($row[10]) ?: 2025;

            // Tableau des mois en lettres (français)
            $mois_fr = [
                1 => "Janvier", 2 => "Février", 3 => "Mars", 4 => "Avril", 5 => "Mai", 6 => "Juin",
                7 => "Juillet", 8 => "Août", 9 => "Septembre", 10 => "Octobre", 11 => "Novembre", 12 => "Décembre"
            ];

            $moisEnLettres = $mois_fr[$moisDebut] ?? "Janvier"; // par défaut


            // 1. Type d’occupation
            $type = TypeOccu::whereRaw('LOWER(TRIM(nom)) = ?', [$typeOccuNom])->first();
            if (!$type) {
                $type = TypeOccu::create(['nom' => ucfirst($typeOccuNom)]);
            }

            // 2. Galerie
            $galerie = Galerie::whereRaw('LOWER(TRIM(nom)) = ?', [$galerieNom])->first();
            if (!$galerie) {
                $galerie = Galerie::create([
                    'nom' => ucfirst($galerieNom),
                    'commune_id' => 8,
                ]);
            }

            // 3. Occupation
            $occupation = Occupation::firstOrCreate([
                'ref' => $ref,
                'galerie_id' => $galerie->id,
                'type_occu_id' => $type->id,
                'montant' => $loyerMontant,
            ], [
                'multiple' => 0,
                'actif' => 1,
            ]);

            // 4. Locataire (éviter doublons)
            $locataire = Locataire::where([
                ['noms', '=', $nomComplet],
                ['num_occupation', '=', $numOccu],
                ['occupation_id', '=', $occupation->id],
            ])->first();

            if (!$locataire) {
                // Génération du matricule unique
                $lastId = Locataire::max('id') + 1;
                $matricule = 'MIL' . date('Y') . '' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

                $locataire = Locataire::create([
                    'matricule' => $matricule,
                    'noms' => $nomComplet,
                    'tel' => null,
                    'garantie' => $garantieMontant,
                    'nbr' => $moisDettes,
                    'mp' => $moisDebut,
                    'ap' => $anneeDebut,
                    'occupation_id' => $occupation->id,
                    'num_occupation' => $numOccu,
                    'actif' => 1,
                ]);
                $this->mainImport->totalImported++;
            }

            // // 5. Loyer
            // Loyer::create([
            //     'mois' => $moisEnLettres,
            //     'annee' => $anneeDebut,
            //     'montant' => $loyerMontant,
            //     'locataire_id' => $locataire->id,
            //     'users_id' => auth()->id() ?? 1,
            //     'garantie' => 0,
            // ]);

             // 5. Loyer
            // Loyer::create([
            //     'mois' => $moisEnLettres,
            //     'annee' => $anneeDebut,
            //     'montant' => $loyerMontant,
            //     'locataire_id' => $locataire->id,
            //     'users_id' => auth()->id() ?? 1,
            //     'garantie' => 0,
            // ]);

            // 6. Garantie
            Garantie::create([
                'montant' => $garantieMontant,
                'locataire_id' => $locataire->id,
                'users_id' => auth()->id() ?? 1,
                'restitution' => 0,
            ]);
        }
    }
}
