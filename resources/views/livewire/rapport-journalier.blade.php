@vite('resources/css/app.css')
@php
    use App\Models\Loyer;
    use App\Models\Divers;

@endphp 
<div style="overflow: scroll">
    <div class="flex justify-between">
        <h1 class="text-2xl font-bold" style="padding-bottom: 25px;">Journal de caisse</h1>
    </div>
    <table class="overflow-x-scroll">
        <thead class="bg-gray-100 dark:bg-gray-700" style="background-color: #ababab9f">
            <tr class=" ">
                <th scope="col" colspan="3" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Noms des Clients 
                </th>
                <th scope="col" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Galeries
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
                                $this->lesMois[$moisEncours] 
                            .'</th>';
                        }
                        if ($moisEncours < 0 || $moisEncours == 0 ){
                            echo '<th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">'.
                                $this->lesMois[$moisEncours + 12]
                            .'</th>';
                        }
                        if($moisEncours > 12){
                            echo '<th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">'.
                                $this->lesMois[$moisEncours - 13 ]
                            .'</th>';
                        }
                        if($moisEncours >=1 && $moisEncours<10){
                            echo '<th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">'.
                                $this->lesMois['0'.$moisEncours]
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
                        Dettes
                    </td>
                    @php
                    $moisAff = '';
                    for ($i=0; $i < 5; $i++) {
                        $moisEncours = (intVal(now()->format('m'))-4) + $i;
                        if ($moisEncours >= 10 && $moisEncours <= 12) {
                            echo '<td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">'. 
                                        $this->lesMois[$moisEncours] == $loyer->mois ? $loyer->montant.' $' : 0 .' $'
                                        
                                 .'</td>';
                                 $td+= $loyer->montant;
                            
                        }
                        if ($moisEncours < 0 || $moisEncours == 0 ){
                            echo '<td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">'. 
                                        $moisAff=$this->lesMois[$moisEncours + 12] == $loyer->mois ? $loyer->montant.' $' : 0 .' $'
                                 .'</td>';
                                 $tj+= $loyer->montant;
                                
                        }
                        if($moisEncours > 12){
                            echo '<td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">'. 
                                        $moisAff = $this->lesMois[$moisEncours - 13 ] == $loyer->mois ? $loyer->montant.' $' : 0 .' $'
                                .'</td>';
                                $tf+= $loyer->montant;
                        }
                        if($moisEncours >=1 && $moisEncours<10){
                            echo '<td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">'.
                                        $moisAff = $this->lesMois['0'.$moisEncours] == $loyer->mois ? $loyer->montant.' $' : 0 .' $'
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
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$lm}}
                </td>
                
            </tr>
        </tfoot>
    </table>
{{--     <p>{{$data}}</p>
 --}}</div>
