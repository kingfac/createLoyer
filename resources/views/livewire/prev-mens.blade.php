@vite('resources/css/app.css') 
<div>
    <h1 class="text-2xl font-bold" style="padding-bottom: 25px;">Prévision mensuelle</h1>
    <?php 
        use App\Models\Loyer;
        use App\Models\Locataire;
    ?>
    @php
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
    <div class="overflow-x-auto shadow-md sm:rounded-lg">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden ">
                <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="p-4">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-blue-600 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 p-2"></div>
                                    <label for="checkbox-all" class="sr-only">checkbox</label>
                                </div>
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                N°
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Galerie
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Loyer total
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Dettes antérieures
                            </th>

                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @php
                            $_id = 0;
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
                        
                        @php
                            /* ...........nombre d'occupations dans une galerie..................... */
                            
                            /* ...........nombre de locataires dans une occupation.................. */
                            
                            foreach($dt->occupations as $occup){
                                
                                    $sommeLoyerApay = Locataire::all()->where('occupation_id',$occup->id)->where('actif',true)->sum('occupation.montant');

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

                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                            <td class="p-4 w-4">

                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap ">
                                {{$dt->id}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap ">
                                {{$dt->nom}}-{{$dt->num}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap ">
                                 {{$sommeLoyerApay}} $
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap ">
                                {{$total}} $
                            </td>
                        </tr>
                        @php
                            $totLoyer += $sommeLoyerApay;
                            $sommeLoyerApay = 0;
                            $totDette += $total;
                            $total = 0;
                    
                        @endphp
                        @endforeach
                        <tr class="bg-gray-600 dark:hover:bg-gray-700 text-white">
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap ">
                                
                            </td>
                            <td class="py-4 px-6 text-sm font-medium  whitespace-nowrap ">
                                Total
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap ">
                                
                            </td>
                            <td class="py-4 px-6 text-sm font-medium whitespace-nowrap ">
                                {{ $totLoyer }} $
                            </td>
                            <td class="py-4 px-6 text-sm font-medium whitespace-nowrap ">
                                {{$totDette}} $
                            </td>
                        </tr>
                        <tr class="bg-gray-200 text-black ">
                            <td class="py-4 px-6 text-sm font-medium  whitespace-nowrap ">
                            </td>
                            <td colspan="2" class="py-4 px-6 text-sm font-medium  whitespace-nowrap ">
                                Total prévisions mensuelles 
                            </td>
                            <td colspan="2" class="py-4 px-6 text-sm font-medium  whitespace-nowrap text-center">
                                {{$totLoyer}} + {{$totDette}} = {{$totLoyer + $totDette }} $
                            </td>
                        </tr>
                       
                    </tbody>
                </table>
            </div>
        </div>

</div>
