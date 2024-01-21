<link rel="stylesheet" href="{{public_path('css.css')}}"> 


<div class="w-full">

    <div class=" text-center">
        <img src="{{public_path('logo.png')}}">
        <h2>MILLE ET UNE MERVEILLE</h2>
    </div>
    
    <div class="text-center b-2 bg-gray-500 mb-2">TOTAL GARANTIES</div>
    
    
    
    <table class="">
        <thead class="bg-gray-100 dark:bg-gray-700" style="background-color: #ababab9f">
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
                    Montant Garanties
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
                <td class="p-4 w-4">
                    {{$loop->index + 1}}
                </td>
                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{$dt->noms}}
                </td>
                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{$dt->occupation->galerie->nom}}
                </td>
                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{$dt->occupation->typeOccu->nom}}
                </td>
                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{-- {{$dt->occupation->montant}}$ --}}
                    
                    @foreach ($dt->garanties as $gar)
                    @if ($gar->restitution == false)
                        @php
                            $totalg += $gar->montant;
                        @endphp
                    @endif
                    @endforeach
                    {{$totalg}} $
                </td>
               
            </tr>
            
            
            @php
                $total += $totalg;
            @endphp
            @endforeach
           <tr class="text-xl border-b" style=" font:bold; size:1.6em;">
            <td colspan="4" class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">Total</td>
            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$total}} $</td>
           </tr>
        </tbody>
    </table>
    @php
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y');
    @endphp

</div>    

