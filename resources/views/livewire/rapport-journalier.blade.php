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
                    Periode
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
            @foreach ($data as $loyer)
            
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                    <td colspan="3" class="border w-32 py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                        {{$loyer->locataire->noms}}
                    </td>
                    <td class="border py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                        {{$loyer->locataire->occupation->galerie->nom}}
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
                        {{$loyer->mois}}
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
                            
                        }
                        if ($moisEncours < 0 || $moisEncours == 0 ){
                            echo '<td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">'. 
                                        $moisAff=$this->lesMois[$moisEncours + 12] == $loyer->mois ? $loyer->montant.' $' : 0 .' $'
                                 .'</td>';
                                
                        }
                        if($moisEncours > 12){
                            echo '<td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">'. 
                                        $moisAff = $this->lesMois[$moisEncours - 13 ] == $loyer->mois ? $loyer->montant.' $' : 0 .' $'
                                .'</td>';
                        }
                        if($moisEncours >=1 && $moisEncours<10){
                            echo '<td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">'.
                                        $moisAff = $this->lesMois['0'.$moisEncours] == $loyer->mois ? $loyer->montant.' $' : 0 .' $'
                                 .'</td>';
                        }
                    }
                    @endphp
                    
                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                        {{now()->format('d-m-Y')}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
{{--     <p>{{$data}}</p>
 --}}</div>
