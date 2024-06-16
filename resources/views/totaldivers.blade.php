<link rel="stylesheet" href="{{public_path('css.css')}}"> 


<div class="w-full">
    @php
        use Carbon\Carbon;
    @endphp

    <div class=" text-center">
        <table style=" width:100%; font-size: 1em; font-weight: bold; color:rgb(46, 131, 211)">
            <tr  style="">
                <td  style="">
        
                    <div class="text-start" style="">
                        <h2>MILLE ET UNE MERVEILLE</h2>
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
    
    <div class="text-center b-2 bg-gray-500 mb-2">TOTAL DIVERS</div>
    
    
    
    <table class="" style=" width:100%;">
        <thead class="bg-gray-100 dark:bg-gray-700" style="background-color: #ababab9f">
            <tr>
                <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">N°</th>
                <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Noms 
                </th>
                <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Galerie
                </th>
                <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Occupation
                </th>
                <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Besoin
                </th>
                <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Montant divers
                </th>                            
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
    
            @php
                $_id = 0;
                $ctrR = 0;
                $total = 0;
            @endphp
            
            @foreach ($data as $dt) 
            
            @php
                $ctrR += 1;
                //$total += $dt->montant;
            @endphp
            
            @php
            $totalg = 0
            @endphp
            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 border-b">
                <td class=" px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white w-4">
                    {{$loop->index + 1}}
                </td>
                <td class=" px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{$dt->noms}}
                </td>
                <td class=" px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{$dt->occupation->galerie->nom}}-{{$dt->occupation->galerie->num}}
                </td>
                <td class=" px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{$dt->occupation->typeOccu->nom}}
                </td>
                <td class=" px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    @forelse ($dt->divers as $div)
                        <p>{{$loop->index+=1}}.{{$div->besoin}}   </p>
                    @empty
                        <p>Aucun besoin</p>
                    @endforelse
                    
                </td>
                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{-- {{$dt->occupation->montant}}$ --}}
                    
                    @forelse ($dt->divers as $div)
                        @php
                            $totalg += ($div->qte*$div->cu);
                            @endphp
                            <p>{{($div->qte*$div->cu)}} $</p>
                    @empty
                        <p>0 $</p>
                    @endforelse
                </td>
               
            </tr>
            
            
            @php
                $total += $totalg;
            @endphp
            @endforeach
           
        </tbody>
        <tr class="text-xl border-b" style=" font:bold; size:1.6em;">
            <td colspan="5" class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">Total</td>
            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$total}} $</td>
        </tr>
    </table>
    @php
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y');
    @endphp

</div>    

