<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{public_path('css.css')}}">
</head>
<body class="w-screen">
    @php
        use Carbon\Carbon;
        use App\Models\Loyer;
    
        $Mois1 = [
            '01' => 'Janvier',
            '02' => 'Février',
            '03' => 'Mars',
            '04' => 'Avril',
            '05' => 'Mais',
            '06' => 'Juin',
            '07' => 'Juillet',
            '08' => 'Aout',
            '09' => 'Septembre',
            '10' => 'Octobre',
            '11' => 'Novembre',
            '12' => 'Décembre'
        ];
        $Mois2 = [
            'Janvier' => '01',
            'Février' => '02',
            'Mars' => '03',
            'Avril' => '04',
            'Mais' => '05',
            'Juin' => '06',
            'Juillet' => '07',
            'Aout' => '08',
            'Septembre' => '09',
            'Octobre' => '10',
            'Novembre' => '11',
            'Décembre' => '12'
        ];
        
    @endphp
    <div class=" text-center w-full">
        
        <table style=" width:100%; font-size: 1em; font-weight: bold; color:rgb(46, 131, 211)">
            <tr  style="">
                <td  style="">
        
                    <div class="text-start" style="">
                        <h2>MILLE ET UNE MERVEILLE</h2>
                        <h3>RCCM/15-B-9122</h3>
                        <h3>N.R.C. 53666 - Id. Nat. : 01-910-N 40270K</h3>
                        <h3>Av. Tshuapa N°90 C./Kinshasa</h3>
                        <h3 style=" border-bottom:solid 1px; borcer-bottom-width:100px;">Tel. : 0850758588 - 0816567028</h3>
                    </div>
                </td>
                <td style="text-align:right;" colspan="3">
                    @php
                        $lelo = Carbon::today()->format('d-m-Y');
                    @endphp
                    <h4>Kin, le {{$lelo}}</h4>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="text-center text-xl  b-2 bg-blue-500 mb-2">{{$label}}</div>
    
    <table class="w-full table-auto divide-y divide-gray-200 text-start">
        <thead class="bg-gray-50 dark:bg-white/5">
            <tr class="text-lg font-bold " style="background-color:#abababc6;">
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    N°
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" colspan="2">
                    GALERIE
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    LOYER TOTAL
                </td>
    
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    DETTES ANTERIEURES
                </td>
            </tr>
        </thead>
    
    
    <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
       
        @php
            $_id = 0;
            $tot_lp=0;
            $tot_somme=0;
            $tot_reste=0;
            $num=1;
            
            $ctrR = 0;
            $sommeGarentie = 0;
            $sommeLoyerApay = 0;
            $sommeLoyerPay = 0;
            $voir = 0;
                            
            // $m est le mois parcouru enregistré pour le calcul de somme 
            $total = 0;
            $m = 0; // mois encour de traitement
            $total_mois = 0;
            $somme_mois = [];
            $nbrMois_paye = 0;

            /* total loyer */
            $totLoyer = 0;
            $totDette =  0;
                        
        @endphp
        @foreach ($data as $dt) 
        @if ($_id != $dt->id )
        @php
            $_id = $dt->id;
        @endphp
        <tr class="border-b">
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="">{{$num}}</td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" colspan="2">
                    {{$dt->nom}}-{{$dt->num}}
                </td>

                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    @php
                        $montant = 0;
                        $sommeMont = 0;

                        foreach ($dt->occupations as $key => $value) {
                            if ($value->multiple == true) {
                                $nombre = $value->locataires->where('occupation_id',$value->id)->count();
                                $montant = $nombre * $value->montant;    
                                $sommeMont += $montant;
                            } else {
                                $sommeMont += $value->montant;
                            }
                            $montant = 0;
                        }
                        
                    @endphp
                    {{$sommeMont}}$
                </td>
                <td>
                    @php
                        foreach($dt->occupations as $occup){
                                
                                // $sommeLoyerApay = Locataire::all()->where('occupation_id',$occup->id)->where('actif',true)->sum('occupation.montant');

                                foreach ($occup->locataires as $loc) {
                                    if($loc->actif){
                                            /* Parcourir les loyers du locataire */ 
                                            foreach (Loyer::where('locataire_id', $loc->id)->orderByRaw('annee, mois')->get() as $loy)
                                            {
                                                    //convertir mois en nombre
                                                    $mloyer = intval($Mois2[$loy->mois]);
                                                    //si ce n'est pas le meme mois qu'on traite
                                                    if($m != $mloyer){
                                                        if($m != 0){
                                                            //s'il a une dette par rapport a ce mois
                                                            // $total_mois = 
                                                            if ($total_mois < $loc->occupation->montant) {
                                                                $total += $loc->occupation->montant - $total_mois;
                                                            }
                                                        }
                                                        //chargement du mois suivant et calcul de la somme des loyers payess
                                                        $m = $mloyer;
                                                        $total_mois = 0;
                                                        $total_mois += $loy->montant;
                                                        $nbrMois_paye++;
                                                    }
                                                    else{
                                                        $total_mois += $loy->montant;
                                                    }
                                            }
                                            /* Affichage des arrieres s'il y a */
                                                $Nba = date("Y") - $loc->ap; //nombre d'annee
                                                $mois_encours = date("m"); //mois encours
                                                $nbMois = ((13 * $Nba) - $loc->mp) + date("m"); //nombre de mois total
                                                $x_encour = ($Nba == 0) ? $mois_encours :  (13 - $loc->mp - $nbrMois_paye); // nombre de mois de l'annee precedente s'il y a 
                                            
                                            

                                            /* Affichage de mois d'arrieressss */
                                            if ($loc->ap != null)
                                            {                                                       
                                                    if ($x_encour >= 0){
                                                        if ($x_encour > 0){    
                                                            if ($Nba != 0){
                                                                for ($i = ($loc->mp + $nbrMois_paye); $i <= 12; $i++){
                                                                        $total += $loc->occupation->montant;
                                                                }
                                                            }else{
                                                                /* Si tout se passe dans la meme annee */
                                                                for ($i = ($loc->mp + $nbrMois_paye); $i <= $x_encour; $i++){
                                                                        $total += $loc->occupation->montant;
                                                                }
                                                            }
                                                        }
                                                        if ($Nba > 0){   
                                                            for ($i = 1; $i <= $mois_encours; $i++){
                                                                $total += $loc->occupation->montant;
                                                            }
                                                        }
                                                    }
                                            }

                                            
                                            
                                    }
                                       
                                }
                            }
                    @endphp
                    {{$total}} $
                </td>                
    
        </tr>
        @endif
        @php
            $totLoyer += $sommeLoyerApay;
            $sommeLoyerApay = 0;
            $totDette += $total;
            $total = 0;                  
         @endphp
        @endforeach
        <tfoot>
            <tr class="border-b bg-gray-100">
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Total
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" colspan="2">
                    
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{ $totLoyer }} $
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$totDette}} $
                </td>
            </tr>
            <tr>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Total prévisions mensuelles 
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$totLoyer}} + {{$totDette}} = {{$totLoyer + $totDette }} $
                </td>
            </tr>
        </tfoot>
    </tbody>
    
    
    </table>

    @php
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y');
    @endphp

    <div class="w-full" style=" text-align:right; margin-top:30px;">
        <p>Aujourd'hui le, {{$lelo}}</p>
    </div>
</body>
</html>