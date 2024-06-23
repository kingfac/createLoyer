<link rel="stylesheet" href="{{public_path('css.css')}}"> 

<div class="w-screen">
    @php
        use Carbon\Carbon;
        use App\Models\Loyer;
        use App\Models\Divers;
        $tot_div = 0;

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
    @endphp

    <div class=" text-center">
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
        <h2>{{$label}}</h2>
    </div>
    
    
    <table class="overflow-x-scroll">
        <thead class="bg-gray-100 dark:bg-gray-700" style="background-color: #ababab9f">
            <tr class=" ">
                <th scope="col" colspan="3" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Locataire 
                </th>
                <th scope="col" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Galerie
                </th>
                <th scope="col" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    libelle
                </th>
                <th scope="col" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Periode(Loyer)
                </th>  
                <th scope="col" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Garantie
                </th>  
                <th scope="col" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Divers
                </th>
                <th scope="col" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Dettes
                </th> 
                @php
                    $moisEncours = intVal(now()->format('m'))-4;
                    for($i = 0 ; $i < 5; $i++) {
                        $moisEncours = (intVal(now()->format('m'))-4) + $i;
                        if ($moisEncours >= 10 && $moisEncours <= 12) {
                            echo '<th scope="col" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">'.
                                $lesMois[$moisEncours] 
                            .'</th>';
                        }
                        if ($moisEncours < 0 || $moisEncours == 0 ){
                            echo '<th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">'.
                                $lesMois[$moisEncours + 12]
                            .'</th>';
                        }
                        if($moisEncours > 12){
                            echo '<th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">'.
                                $lesMois[$moisEncours - 13 ]
                            .'</th>';
                        }
                        if($moisEncours >=1 && $moisEncours<10){
                            echo '<th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">'.
                                $lesMois['0'.$moisEncours]
                            .'</th>';    
                        }
                    }
                @endphp
                <th scope="col" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Datte
                </th>    
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
            @php
                $lm=0;
                $tot_div=0;
                $td=0;
                $tj=0;
                $tf=0;
                $tm=0;
                $tv=0;
            @endphp
            @foreach ($data as $loyer)
                
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                    <td colspan="3" class="border w-32 py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                        {{$loyer->locataire->noms}}
                    </td>
                    <td class="border py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                        {{$loyer->locataire->occupation->galerie->nom}}-{{$loyer->locataire->occupation->galerie->num}}
                    </td>
                    <td class="border py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                        @php
                            $result ="";
                            if($loyer->montant == $loyer->locataire->occupation->montant){
                                $result = "Loyer";
                            }else{
                                $loyerParMois = Loyer::where([
                                    "locataire_id"=>$loyer->locataire_id,
                                    "mois"=>$loyer->mois
                                ])
                                ->where('id','<=',$loyer->id)
                                ->sum('montant');
                                if($loyer->locataire->occupation->montant - $loyerParMois){
                                    $result = "Avance";
                                } else {
                                    $result = "Solde";
                                }
                            }
                        @endphp
                        {{$result}}
                    </td>
                    <td class="border py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                        {{$loyer->mois}}({{$loyer->montant}}$)
                        @php
                            $lm+=$loyer->montant;   
                        @endphp
                    </td>
                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                        @if ($loyer->garantie == true)
                            {{$loyer->montant}} $
                        @else
                            Sans garantie
                        @endif
                    </td>
                    <td class="border py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                        @php
                            $diver = Divers::where([
                                "locataire_id"=>$loyer->locataire_id,
                            ])->whereRaw("DATE(created_at)=?",now()->format('Y-m-d'))->first();
                        @endphp
                        @if ($diver != null)
                            {{ $diver->qte * $diver->cu}} $
                            @php
                                $tot_div+=$diver->qte * $diver->cu;
                            @endphp
                        @else
                            0 $
                        @endif
                    </td>
                    <td class="border py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                        @php
                            $sommeLoyerMois = Loyer::where('locataire_id',$loyer->locataire_id)
                                                   ->where('mois', $loyer->mois)
                                                //    ->where('annee', $loyer->annee)
                                                   ->where('created_at','<=', $loyer->created_at)
                                                   ->sum('montant');
                            $loyerApayer = $loyer->locataire->occupation->montant;
                            $dettes =$loyerApayer - $sommeLoyerMois;
                            
                        @endphp
                        @if ($dettes > 0)
                            {{$dettes}} $
                        @endif
                    </td>
                    @php
                    $moisAff = '';
                    for ($i=0; $i < 5; $i++) {
                        $moisEncours = (intVal(now()->format('m'))-4) + $i;
                        if ($moisEncours >= 10 && $moisEncours <= 12) {
                            echo '<td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">'. 
                                        $lesMois[$moisEncours] == $loyer->mois ? $loyer->montant.' $' : null
                                        
                                 .'</td>';
                                 $td+= $loyer->montant;
                            
                        }
                        if ($moisEncours < 0 || $moisEncours == 0 ){
                            echo '<td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">'. 
                                        $moisAff=$lesMois[$moisEncours + 12] == $loyer->mois ? $loyer->montant.' $' :null
                                 .'</td>';
                                 $tj+= $loyer->montant;
                                
                        }
                        if($moisEncours > 12){
                            echo '<td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">'. 
                                        $moisAff = $lesMois[$moisEncours - 13 ] == $loyer->mois ? $loyer->montant.' $' : null
                                .'</td>';
                                $tf+= $loyer->montant;
                        }
                        if($moisEncours >=1 && $moisEncours<10){
                            echo '<td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">'.
                                        $moisAff = $lesMois['0'.$moisEncours] == $loyer->mois ? $loyer->montant.' $' : null
                                 .'</td>';
                                 $tm+= $loyer->montant;
                        }
                    }
                    @endphp
                    
                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                        {{now()->format('d-m-Y')}}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="border-b" style=" background-color:rgb(194, 189, 189)">
                <td class="fi-ta-cell  text-center  p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="">Totaux</td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                </td>
                <td class="fi-ta-cell text-center  p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style=""></td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    
                </td>
                <td class="fi-ta-cell  text-center p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style=""></td>
                <td class="fi-ta-cell text-center p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$lm}} $
                </td>
                <td class="fi-ta-cell text-center  p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style=""></td>
                <td class="fi-ta-cell  text-center p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$tot_div}} $
                </td>
                <td class="fi-ta-cell text-center  p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style=""></td>
                <td class="fi-ta-cell text-center  p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{-- {{$td}} --}}
                </td>
                <td class="fi-ta-cell  text-center p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style=""></td>
                <td class="fi-ta-cell text-center  p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{-- {{$tj}} --}}
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{-- {{$tf}} --}}
                </td>
                <td class="fi-ta-cell p-0 text-center first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$lm}}
                </td>
                
            </tr>
        </tfoot>
    </table>

    @php
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y');
    @endphp

    <div class="w-full" style=" text-align:right; margin-top:30px;">
        <p>Aujourd'hui le, {{$lelo}}</p>
    </div>
</div>