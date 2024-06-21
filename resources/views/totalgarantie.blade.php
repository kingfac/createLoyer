<link rel="stylesheet" href="{{public_path('css.css')}}"> 
@php
    use Carbon\Carbon;
@endphp

<div class="w-full">

    <div class=" text-center">
        {{-- <img src="{{public_path('logo.png')}}"> --}}
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
    
    <div class="text-center b-2 bg-gray-500 mb-2">TOTAL GARANTIES</div>
    
    


<div class="overflow-x-auto shadow-md sm:rounded-lg">
    <div class="inline-block min-w-full align-center">
        <div class="overflow-hidden ">
            <table class="w-full table-auto divide-y divide-gray-200 text-start">
                <thead class="bg-gray-100 dark:bg-gray-700" style="background-color: rgb(218, 218, 218)">
                    <tr class="border-b">
                        <th  scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">N°</th>
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
                            Garantie payée
                        </th>            
                        <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Garantie utilisée
                        </th>    
                        <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Reste
                        </th>                    
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">

                    @php
                        $_id = 0;
                        $ctrR = 0;
                        $total = 0;
                        $totalGarLoyer=0;
                        $totalGu=0;
            
                    @endphp
                    
                    @foreach ($data as $dt) 
                    
                    @php
                        $ctrR += 1;
                        //$total += $dt->montant;
                    @endphp
                    
                    @php
                    $totalg = 0
                    @endphp
                    <tr class="border-b">
                        <td class="p-4 w-4">
                            {{$loop->index + 1}}
                        </td>
                        <td class="fi-ta-cell px-6 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" >
                            {{$dt->noms}}
                        </td>
                        <td class="fi-ta-cell px-6 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" >
                            {{$dt->occupation->galerie->nom}}-{{$dt->occupation->galerie->num}}
                        </td>
                        <td class="fi-ta-cell px-6 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" >
                            {{$dt->occupation->typeOccu->nom}}
                        </td>
                        <td class="fi-ta-cell px-6 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" >
                            {{-- {{$dt->occupation->montant}}$ --}}
                            
                            @foreach ($dt->garanties as $gar)
                            @if ($gar->restitution == false)
                                @php
                                    $totalg += $gar->montant;
                                @endphp
                            @endif
                            @endforeach

                            {{-- on recupère tous les loyers payés avec la garantie --}}
                            @foreach ($dt->loyers as $loyer)
                                @if ($loyer->garantie)
                                    @php
                                        $totalGarLoyer += $loyer->montant;
                                    @endphp
                                @endif
                            @endforeach
                            @php
                                $totalGu+=$totalGarLoyer;
                            @endphp
                            {{$totalg}} $
                        </td>
                        <td class="fi-ta-cell px-6 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" >
                            {{$totalGarLoyer}} $
                        </td>
                        <td class="fi-ta-cell px-6 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" >
                            {{$totalg-$totalGarLoyer}} $
                        </td>
                       
                    </tr>
                    
                    
                    @php
                        $total += $totalg;
                    @endphp
                    @endforeach
                   <tr class="text-xl bg-gray-200" style=" font:bold; size:1.6em;">
                    <td colspan="4" class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">Totaux</td>
                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$total}} $</td>
                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$totalGu}} $</td>
                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$total-$totalGu}} $</td>

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
    @if ($ctrR == 0)
    <div class="flex justify-center items-center text-2xl text-red-400 p-10">
        <h1>Pas de données disponibles...</h1>
    </div>
    @endif
</div>


</div>    

