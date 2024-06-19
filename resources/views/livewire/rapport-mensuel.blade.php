{{-- <div>
    <h1 class="text-2xl font-bold" style="padding-bottom: 25px;">Rapport du mois de {{$mois}} {{$annee}} </h1>
    <div class="flex  justify-between">
        <h1 class="text-2xl font-bold" style="padding-bottom: 25px;">Rapport du mois de {{$mois}} {{$annee}} </h1>
        
        <x-filament::icon-button
            icon="heroicon-o-printer"
            tag="a"
            label="imprimer"
            tooltip="Imprimer"
            href="/storage/pdf/doc.pdf"
            target="_blank"
            
        />
    </div>
    {{$this->table}}
</div> --}}



<div class="w-full">
    <link rel="stylesheet" href="{{public_path('css.css')}}"> 
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


    <div class="flex  justify-between">
        <h1 class="text-2xl font-bold" style="padding-bottom: 25px;">Rapport du mois de {{$mois}} {{$annee}} </h1>
        
        <x-filament::icon-button
            icon="heroicon-o-printer"
            tag="a"
            label="imprimer"
            tooltip="Imprimer"
            href="/storage/pdf/doc.pdf"
            target="_blank"
            
        />
    </div>
    
    
    
    


<div class="overflow-x-auto shadow-md sm:rounded-lg">
    <div class="inline-block min-w-full align-center">
        <div class="overflow-hidden ">
            <table style=" background-color: rgb(223, 223, 223)">
                <thead class="bg-gray-100 dark:bg-gray-700" style="background-color:rgb(161, 161, 161)">
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
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @forelse ($galeries as $galerie)
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$loop->index +1 }}
                            </td>
                            <td class="py-4  text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$galerie->nom}}-{{$galerie->num}}
                            </td>
                            <td class="py-4 px-5 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{getAnciennesGaranties($galerie,$mois,$annee)}}$
                                @php
                                    $aG += getAnciennesGaranties($galerie,$mois,$annee);
                                @endphp
                            </td>
                            <td class="py-4 px-5 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{getNouvellesGaranties($galerie,$mois,$annee)}}$
                                @php
                                    $nG += getNouvellesGaranties($galerie,$mois,$annee);
                                @endphp
                            </td>
                            <td class="py-4 px-5 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{getDettesAnterieuresPercues($galerie,$mois,$annee)}}$
                                @php
                                    $dA += getDettesAnterieuresPercues($galerie,$mois,$annee);
                                @endphp
                            </td>
                            <td class="py-4 px-5 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{MontantMois($galerie,$mois,$annee)}}$
                                @php
                                    $cM += MontantMois($galerie,$mois,$annee);
                                @endphp
                            </td>
                            <td class="py-4 px-5 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{MontantMoisSuivant($galerie,$mois,$annee)}}$

                                @php
                                    $mS += MontantMoisSuivant($galerie,$mois,$annee);
                                @endphp
                            </td>
                            <td class="py-4 px-5 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
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
                            <td class="py-4  px-5 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{getSomme($galerie,$mois)}}$

                                @php
                                    $mA += getSomme($galerie,$mois);
                                @endphp
                            </td>
                            <td class="py-4  px-5 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
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
                            <td class="py-4  px-5 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$result}}%
                            </td>
                            <td class="py-4  px-5 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{getSortieDette($galerie,$mois,$annee)}}
                                @php
                                    $sD += getSortieDette($galerie,$mois,$annee);
                                @endphp
                            </td>

                        </tr>
                    @empty
                        
                    @endforelse

                    <tr class="text-xl bg-gray-200" style=" font:bold; size:1.6em;">
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"> Totaux</td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"> </td>

                        <td class="py-4 px-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$aG}} $</td>
                        <td class="py-4 px-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$nG}} $</td>
                        <td class="py-4 px-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$dA}} $</td>
                        <td class="py-4 px-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$cM}} $</td>
                        <td class="py-4 px-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$mS}} $</td>
                        <td class="py-4 px-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$tP}} $</td>
                        <td class="py-4 px-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$mA}} $</td>
                        <td class="py-4 px-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$mN}} $</td>
                        <td class="py-4 px-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$tR}}%</td>
                        <td class="py-4 px-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$sD}}</td>

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

