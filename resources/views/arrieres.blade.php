{{--  --}}
<link rel="stylesheet" href="{{public_path('css.css')}}"> 



{{-- @vite('resources/css/app.css') --}}
<div class="w-full">

    <?php 
        use App\Models\Loyer;
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

    <link rel="stylesheet" href="{{asset('build/assets/app-3e76f9e4.css') }}">
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}

    @php
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y').' à '.$lelo->format('H:i');
    @endphp
<div class="font-serif" >
    <table style=" width:100%; font-size: 1em; font-weight: bold; color:rgb(46, 131, 211)">
        <tr  style="">
            <td  style="">

                <div class="text-start " style="">
                    <h2>MILLE ET UNE MERVEILLE</h2>
                    <h3>RCCM/15-B-9122</h3>
                    <h3>N.R.C. 53666 - Id. Nat. : 01-910-N 40270K</h3>
                    <h3>Av. Tshuapa N°90 C./Kinshasa</h3>
                    <h3 style="">Tel. : 0850758588 - 0816567028</h3>
                </div>
            </td>
            <td style="text-align:right;" colspan="3">
                <h4>Kin, le {{$lelo}}</h4>
                {{-- <h4 class="p-2 bg-gray-200" style="width: 100%;">Montant :  <b style="padding: 5px; background-color:rgb(98, 172, 241); width:100%;color:white">{{ $record->montant }} $ </b></h4>
                <h4>Loyer de : {{$record->mois}}-{{$record->annee}}</h4> --}}
                <h4>Intervenant : {{Auth::user()->name}}</h4>
            </td>
        </tr>
    </table>
    <div class="text-center b-2 bg-gray-500 mb-2">Arriérés des locataires</div>



    <div class="overflow-x-auto shadow-md sm:rounded-lg">
        <div class="inline-block  align-center">
            <div class="overflow-hidden ">
                <table style="width:100%; border-collapse:collapse" >
                    <thead class="bg:gray-100 dark:bg-gray-700" style="background-color: rgb(218, 218, 218)">
                        <tr>
                            <td scope="col" class="py-1 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">N°</td>
                            <th scope="col" class="py-1  px-3 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Noms 
                            </th>
                            <th scope="col" class="py-1 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Galeries
                            </th>
                            <th scope="col" class="py-1 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Occupations
                            </th>
                            <th scope="col" class="py-1 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Loyers
                            </th>      
                            <th scope="col" class="py-1 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Arriérés
                            </th>         
                                          
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">

                        @php
                            $_id = 0;
                            $ctrR = 0;
                            $total = 0;
                        @endphp
                        
                        @foreach ($locataires as $loc)
                        
                        @php
                            $ctrR += 1;
                            //$total += $loc->montant;
                        @endphp
                        
                        @php
                        $totalg = 0
                        @endphp
                        <tr class="border-b">
                            <td class="fi-ta-cell px-6 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                {{$loop->index + 1}}
                            </td>
                            <td class="fi-ta-cell px-6 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                {{$loc->noms}}
                            </td>
                            <td class="fi-ta-cell px-6 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                {{$loc->occupation->galerie->nom}}-{{$loc->occupation->galerie->num}}
                            </td>
                            <td class="fi-ta-cell px-6 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                {{$loc->occupation->typeOccu->nom}}
                            </td>
                            <td class="fi-ta-cell px-6 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                {{$loc->occupation->montant}}$
                            </td>
                            <td class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                {{-- {{$loc->mp}}
                                {{$loc->ap}} --}}
                                @php
                                    // $m est le mois parcouru enregistré pour le calcul de somme 
                                    $m = 0; // mois encour de traitement
                                    $total_mois = 0;
                                    $somme_mois = [];
                                    $nbrMois_paye = 0;
                                    $rapport = [];
                                    $loyers = Loyer::where('locataire_id', $loc->id)->orderByRaw('created_at')->get();
                                @endphp
                                {{-- Parcourir les loyers du locataire --}}

                                @foreach ($loyers as $loy)
                                    
                                    @php
                                        //convertir mois en nombre
                                        $mloyer = intval($Mois2[$loy->mois]);
                                        //si ce n'est pas le meme mois qu'on traite
                                        if($m != $mloyer){
                                            if($m != 0){
                                                //s'il a une dette par rapport a ce mois
                                                if ($total_mois < $loc->occupation->montant) {
                                                    @endphp
                                                    <p>{{$loyers[$loop->index-1]->mois}} : {{$total_mois}} / {{$loc->occupation->montant}}</p>
                                                    @php
                                                    $total += $loc->occupation->montant - $total_mois;
                                                    $rapport[] = [$loyers[$loop->index-1]->mois ,$total_mois ,$loc->occupation->montant, date("Y")-1];
                                                }
                                            }
                                            //chargement du mois suivant et calcul de la somme des loyers payess
                                            $m = $mloyer;
                                            $total_mois = 0;
                                            $total_mois += $loy->montant;
                                            $nbrMois_paye++;
                                            if(count($loyers) == 1 && $loy->montant != $loc->occupation->montant){
                                                $total += $loc->occupation->montant - $total_mois;
                                                $rapport[] = [$loy->mois ,$total_mois ,$loc->occupation->montant, date("Y")-1];
                                            }
                                        }
                                        else{
                                            $total_mois += $loy->montant;
                                        }
                                    @endphp
                                @endforeach
                                {{-- Affichage des arrieres s'il y a --}}
                                @php
                                    $Nba = date("Y") - $loc->ap; //nombre d'annee
                                    $mois_encours = date("m"); //mois encours
                                    $nbMois = ((13 * $Nba) - $loc->mp) + date("m"); //nombre de mois total
                                    $x_encour = ($Nba == 0) ? $mois_encours :  (13 - $loc->mp - $nbrMois_paye); // nombre de mois de l'annee precedente s'il y a 
                                @endphp
                                
                                <p>

                                    {{-- Affichage de mois d'arrieressss --}}
                                    @if ($loc->ap != null)
                                        
                                        {{-- <p>Nombre de mois  : {{$nbrMois_paye}} / {{$nbMois}} et {{$x_encour}}</p> --}}
                                        @if ($x_encour >= 0)
                                            @if ($x_encour > 0)    
                                            {{-- <p class="py-2" style="color: blue">Année  : {{$loc->ap}} </p>
                                            <hr> --}}
                                                @if ($Nba != 0)
                                                    @for ($i = ($loc->mp + $nbrMois_paye); $i <= 12; $i++)
                                                        <p>{{$Mois1[$i > 9 ? $i : "0".$i]}} : 0/{{$loc->occupation->montant}}</p>
                                                        @php
                                                            $total += $loc->occupation->montant;
                                                            $rapport[] = [$Mois1[$i > 9 ? $i : "0".$i] ,0 ,$loc->occupation->montant, date("Y")-1];
                                                        @endphp
                                                    @endfor
                                                @else
                                                    {{-- Si tout se passe dans la meme annee --}}
                                                    @for ($i = ($loc->mp + $nbrMois_paye); $i <= $x_encour; $i++)
                                                        <p>{{$Mois1[$i > 9 ? $i : "0".$i]}} : 0/{{$loc->occupation->montant}}</p>
                                                        @php
                                                            $total += $loc->occupation->montant;
                                                            $rapport[] = [$Mois1[$i > 9 ? $i : "0".$i] ,0 ,$loc->occupation->montant, date("Y")-1];
                                                        @endphp
                                                    @endfor
                                                @endif
                                            @endif
                                            @if ($Nba > 0)   
                                                <p class="py-2" style="color: blue">Année  : {{date("Y")}}</p> 
                                                <hr>  
                                                @for ($i = 1; $i <= $mois_encours; $i++)
                                                    
                                                    <p>{{$Mois1[$i > 9 ? $i : "0".$i]}} : 0/{{$loc->occupation->montant}}</p>
                                                    @php
                                                    $total += $loc->occupation->montant;
                                                    $rapport[] = [$Mois1[$i > 9 ? $i : "0".$i] ,0 ,$loc->occupation->montant, date("Y")];
                                                    @endphp
                                                @endfor
                                            @endif
                                       {{--  @else
                                            <p class="" style="color: green">En ordre</p> --}}
                                        @endif
                                    @else
                                        <p class="" style="color: red">Aucun payement effectué pour ce locataire</p>
                                    @endif
                                </p>
                            </td>
                           
                        </tr>
                        
                        
                        @php
                            $total += $totalg;
                        @endphp
                        @endforeach
                       <tr style="background-color: rgb(218, 218, 218)">
                        <td colspan="5" class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">Total des arriérés</td>
                        <td class="fi-ta-cell px-6 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">{{$total}} $</td>
                       </tr>
                    </tbody>
                </table>
                

                
            </div>
        </div>
       {{--  <div class="text-orange-400">kfkf</div>
        <div class="text-yellow-400">kfkf</div>
        <div class="text-blue-400">kfkf</div>
        <div class="text-green-400">kfkf</div>
        <div class="text-slate-400">kfkf</div> --}}
        @if ($ctrR == 0)
        <div class="flex justify-center items-center text-2xl text-red-400 p-10">
            <h1>Pas de données disponibles...</h1>
        </div>
        @endif
    </div>

    

</div>
