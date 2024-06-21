<link rel="stylesheet" href="{{public_path('css.css')}}"> 

<div class="w-full">
    @php
        use Carbon\Carbon;
        use App\Models\Loyer;
        use App\Models\Galerie;
        use App\Models\Garantie;
        use App\Models\Locataire;
        
    
        $lesMois = [
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
    
        function getAnciennesGaranties($record,$mois,$annee)
        {
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
            $galerie = Galerie::where('id', $record->id)->first();
            $occups = $galerie->occupations;
            $locs=[];
            $somme=[];
            $sommeA=[];
            $sommeL=[];
            $sommeLA=[];
            $mois = intval($Mois2[$mois]);
    
            foreach($occups as $occup){
                foreach ($occup->locataires as $locataire) {
                    # code...
                    if($locataire->actif){
                        array_push($locs, $locataire);
                    }
                }
            }
            foreach ($locs as $loc) {
                // $id = $loc[]
                $loyersGarantie = Loyer::where('locataire_id', $loc->id)->where('garantie',true)->whereMonth('created_at','=', $mois)->whereYear('created_at', $annee)->get();
                $loyersGarantieA = Loyer::where('locataire_id', $loc->id)->where('garantie',true)->get();
    
                $garanties = Garantie::where('locataire_id',$loc->id)->where('restitution',false)->whereMonth('created_at','=', $mois)->whereYear('created_at', $annee)->get();
                $garantiesA = Garantie::where('locataire_id',$loc->id)->where('restitution',false)->get();
                array_push($somme,$garanties->sum('montant'));
                array_push($sommeA,$garantiesA->sum('montant'));
                array_push($sommeL,$loyersGarantie->sum('montant'));
                array_push($sommeLA,$loyersGarantieA->sum('montant'));
            }
            //calcul des anciennes garanties
                           
            return (array_sum($sommeA)-array_sum($sommeLA))-(array_sum($somme)-array_sum($somme));
        }

         /* fonction qui renvoie les nouvelles garanties */
     function getNouvellesGaranties($record,$mois,$annee)
    {
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
        $galerie = Galerie::where('id', $record->id)->first();
        $occups = $galerie->occupations;
        $locs=[];
        $somme=[];
        $mois = intval($Mois2[$mois]);

        foreach($occups as $occup){
            foreach ($occup->locataires as $locataire) {
                # code...
                if($locataire->actif){
                    array_push($locs, $locataire);
                }
            }
        }
        foreach ($locs as $loc) {
            $garanties = Garantie::where('locataire_id',$loc->id)->whereMonth('created_at','=', $mois)->whereYear('created_at', $annee)->get();
            array_push($somme,$garanties->sum('montant'));
        }
        //calcul des anciennes garanties
                       
        return array_sum($somme);
    }

    function getDettesAnterieuresPercues($record,$mois,$annee)
    {
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
        
        /////////////////////////////////////
        $galerie = Galerie::where('id', $record->id)->first();
        $mois = intval($Mois2[$mois]);
        $somme=[];
        $occups = $galerie->occupations;
        $locs=[];

        foreach($occups as $occup){
            array_push($locs, $occup->locataires);
        }
        foreach ($locs as $loc) {
            foreach ($loc as $lo) {     
                if($lo->actif){

                    // ici je n arrive pas a obtenir les loyers dont le mois est < au mois actuel
                    $loyers = Loyer::where('locataire_id',$lo->id)->whereRaw(" (mois) != '$mois' and  YEAR(created_at) = $annee and MONTH(created_at) = '$mois'  and YEAR(created_at) =  '$annee' ")->get();
                    // dd($loyers);
                    // dd($loyers->count(), $lo->noms);
                    if($loyers->count() >= 1){
    
                        foreach ($loyers as $loyer) {
                            // dd(($loyer->mois), $lo->noms,$loyer->id);
                            # code...
                            if(intval($Mois2[$loyer->mois]) <  $mois){
                                array_push($somme,$loyer->montant);  
                            }
                        }
                    }
    
                    // array_push($somme,$loyers->sum("montant"));  
                }           

                
            }
        }

    
        return array_sum($somme);
    }

    function MontantMois($record,$mois,$annee){

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

            $lesMois = [
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
            
        $galerie = Galerie::where('id', $record->id)->first();
       
        $occups = $galerie->occupations;
        $locs=[];
        foreach($occups as $occup){
            array_push($locs, $occup->locataires);
        }
        $somme=[];
        // $mois_suivant =  $lesMois['0'.$mois +1];
       

        foreach ($locs as $loc) {
            foreach ($loc as $lo) {                
                $loyers = Loyer::where('locataire_id',$lo->id)->whereRaw(" (mois) = '$mois' ")->sum('montant');
                array_push($somme,$loyers);
            }
        }

        return array_sum($somme);
    }

    function MontantMoisSuivant($record,$mois,$annee){

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

        $lesMois = [
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
        
    $galerie = Galerie::where('id', $record->id)->first();
    $mois = intval($Mois2[$mois]);

    $occups = $galerie->occupations;
    $locs=[];
    foreach($occups as $occup){
        array_push($locs, $occup->locataires);
    }
    $somme=[];
    // $mois_suivant =  $lesMois['0'.$mois +1];
    $mois_suivant = '0'.$mois +1;
    $mois_suivant = $lesMois[$mois_suivant];

    foreach ($locs as $loc) {
        foreach ($loc as $lo) {                
            $loyers = Loyer::where('locataire_id',$lo->id)->whereRaw(" (mois) = '$mois_suivant' ")->sum('montant');
            array_push($somme,$loyers);
        }
    }

    return array_sum($somme);
}

    function getSomme($gal,$mois):int
    {
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

            $mois = intval($Mois2[$mois]);

            $occups = $gal->occupations;
            $somme_occu = $occups->sum('montant');
            $locs=[];
            $somme_locs=[];
            foreach($occups as $occup){
                array_push($locs, $occup->locataires->where('actif',true));
            }

            foreach ($locs as $loc) {
                foreach ($loc as $lo) {                
                    array_push($somme_locs, $lo->occupation->montant);
                }
            }

            $loyers_locs = array_sum($somme_locs);
            
        return $loyers_locs;
    }

    function getSortieDette($gal,$mois,$annee){
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
        $occups = $gal->occupations;
            $somme_occu = $occups->sum('montant');

        
            $locs=[];
            $somme_sortie_dette=[];
            $moiss = intval($Mois2[$mois]);

            foreach($occups as $occup){
                array_push($locs, $occup->locataires->where('actif',false));
            }

            foreach ($locs as $loc) {
                foreach ($loc as $lo) {           
                    $sm = Garantie::where(['locataire_id' , $lo->id, 'restitution' == true])->whereRaw(["MONTH(created_at) == $moiss "]); 
                    // dd($sm != null);
                    if($sm!= null){

                        array_push($somme_sortie_dette, 1);
                    }
                }
            }

            $nbr = array_sum($somme_sortie_dette);
            return $nbr;
    }

    $aG=0;
    $nG=0;
    $dA=0;
    $cM=0;
    $mS=0;
    $tP=0;
    $mA=0;
    $mN=0;
    $tR=0;
    $sD=0;

    @endphp

    <div class=" text-center">
        {{-- <img src="{{public_path('logo.png')}}"> --}}
        <table style=" width:100%; font-size: 1em; font-weight: bold; color:rgb(46, 131, 211)">
            <tr  style="">
                <td  style="">
        
                    <div class="text-start" style="">
                        <h2>MILLE ET UNE MERVEILLE</h2>
                        <h3>RCCM/15-B-9122</h3>
                        <h3>N.R.C. 53666 - Id. Nat. : 01-910-N 40270 K</h3>
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
    
    <div class="text-center b-2 bg-gray-500 mb-2">{{$label}}</div>
    
    


<div class="overflow-x-auto shadow-md sm:rounded-lg">
    <div class="inline-block min-w-full align-center">
        <div class="overflow-hidden ">
            <table class="w-full table-auto divide-y divide-gray-200 text-start">
                <thead class="bg-gray-100 dark:bg-gray-700" style="background-color: rgb(218, 218, 218)">
                    <tr>
                        <th  scope="col" class="py-3 px-2 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">N°</th>
                        <th scope="col" class="py-3 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Galerie
                        </th>
                        <th scope="col" class="py-3 px-2 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Anciennes garanties
                        </th>
                        <th scope="col" class="py-3 px-2 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Nouvelles garanties
                        </th>
                        <th scope="col" class="py-3 px-2 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Dettes antérieures
                        </th>            
                        <th scope="col" class="py-3 px-2 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Juin
                        </th>    
                        <th scope="col" class="py-3 px-2 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Juillet
                        </th>  
                        <th scope="col" class="py-3 px-2 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Total perçu
                        </th>   
                        <th scope="col" class="py-3 px-2 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Montant attendu
                        </th>       
                        <th scope="col" class="py-3 px-2 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Montant non perçu
                        </th>        
                        <th scope="col" class="py-3 px-2 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Taux de réalisation
                        </th> 
                        <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Sorties avec dettes
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                    @forelse ($galeries as $galerie)
                        <tr class="border-b">
                            <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                {{$loop->index +1 }}
                            </td>
                            <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                {{$galerie->nom}}-{{$galerie->num}}
                            </td>
                            <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                {{getAnciennesGaranties($galerie,$mois,$annee)}}$
                                @php
                                    $aG += getAnciennesGaranties($galerie,$mois,$annee);
                                @endphp
                            </td>
                            <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                {{getNouvellesGaranties($galerie,$mois,$annee)}}$
                                @php
                                    $nG += getNouvellesGaranties($galerie,$mois,$annee);
                                @endphp
                            </td>
                            <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                {{getDettesAnterieuresPercues($galerie,$mois,$annee)}}$
                                @php
                                    $dA += getDettesAnterieuresPercues($galerie,$mois,$annee);
                                @endphp
                            </td>
                            <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                {{MontantMois($galerie,$mois,$annee)}}$
                                @php
                                    $cM += MontantMois($galerie,$mois,$annee);
                                @endphp
                            </td>
                            <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                {{MontantMoisSuivant($galerie,$mois,$annee)}}$

                                @php
                                    $mS += MontantMoisSuivant($galerie,$mois,$annee);
                                @endphp
                            </td>
                            <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                @php
                                    $val = getNouvellesGaranties($galerie,$mois,$annee)+
                                    getDettesAnterieuresPercues($galerie,$mois,$annee)+
                                    MontantMois($galerie,$mois,$annee)+
                                    MontantMoisSuivant($galerie,$mois,$annee);

                                    $tP += $val;
                                @endphp
                                {{
                                    $val
                                }}$

                                
                            </td>
                            <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                {{getSomme($galerie,$mois)}}$

                                @php
                                    $mA += getSomme($galerie,$mois);
                                @endphp
                            </td>
                            <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                {{getSomme($galerie,$mois)-MontantMois($galerie,$mois,$annee)}}$

                                @php
                                    $mN += getSomme($galerie,$mois)-MontantMois($galerie,$mois,$annee);
                                @endphp
                            </td>
                            @php
                                $result=0;
                                if(getSomme($galerie,$mois) != 0){
                                    $result = round(((MontantMois($galerie,$mois,$annee))/getSomme($galerie,$mois))*100,2);
                                }
                                else{
                                    $result = 0;
                                }

                                $tR += $result;
                            @endphp
                            <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                {{$result}}%
                            </td>
                            <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                {{getSortieDette($galerie,$mois,$annee)}}
                                @php
                                    $sD += getSortieDette($galerie,$mois,$annee);
                                @endphp
                            </td>

                        </tr>
                    @empty
                        
                    @endforelse

                    <tr class="text-xl bg-gray-200" style=" font:bold; size:1.6em;">
                        <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3"> Totaux</td>
                        <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3"> </td>

                        <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">{{$aG}} $</td>
                        <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">{{$nG}} $</td>
                        <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">{{$dA}} $</td>
                        <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">{{$cM}} $</td>
                        <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">{{$mS}} $</td>
                        <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">{{$tP}} $</td>
                        <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">{{$mA}} $</td>
                        <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">{{$mN}} $</td>
                        <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">{{$tR}}%</td>
                        <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">{{$sD}}</td>

                    </tr>
                </tbody>
            </table>
            @php
                $lelo = new DateTime('now');
                $lelo = $lelo->format('d-m-Y');
            @endphp

            
        </div>
    </div>
   {{--  <div class="text-orange-400">kfkf</div>
    <div class="text-yellow-400">kfkf</div>
    <div class="text-blue-400">kfkf</div>
    <div class="text-green-400">kfkf</div>
    <div class="text-slate-400">kfkf</div> --}}
   
</div>


</div>    

