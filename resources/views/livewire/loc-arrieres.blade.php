{{--  --}}



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

    <link rel="stylesheet" href="{{asset('build/assets/app-247549ac.css')}}">
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="flex justify-between">
        <h1 class="text-2xl font-bold" style="padding-bottom: 25px;">Arriérés des locataires</h1>
        {{--  {{ $this->form }}
        {{ $this->table }} --}}
        {{-- <x-filament::icon-button
            icon="heroicon-o-printer"
            tag="a"
            label="imprimer"
            tooltip="Imprimer"
            href="/storage/pdf/doc.pdf"
            target="_blank"
                
        /> --}}
    </div>
    <div class="overflow-x-auto shadow-md sm:rounded-lg">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden ">
                <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <td>N°</td>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Noms des locataires 
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Galeries
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Occupations
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Loyers
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
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                            <td class="p-4 w-4">
                                {{$loop->index + 1}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$loc->noms}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$loc->occupation->galerie->nom}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$loc->occupation->typeOccu->nom}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$loc->occupation->montant}}$
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
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
                       <tr class="text-xl" style=" font:bold; size:1.6em;">
                        <td colspan="5" class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">Total des arriérés</td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$total}} $</td>
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
