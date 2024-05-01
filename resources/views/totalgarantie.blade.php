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
    
    <div class="text-center b-2 bg-gray-500 mb-2">TOTAL GARANTIES</div>
    
    
    
    <table class="" style=" width:100%">
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
                    Montant Garantie
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
                <td class="py-1  w-4">
                    {{$loop->index + 1}}
                </td>
                <td class="py-1 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{$dt->noms}}
                </td>
                <td class="py-1 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{$dt->occupation->galerie->nom}}-{{$dt->occupation->galerie->num}}
                </td>
                <td class="py-1 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{$dt->occupation->typeOccu->nom}}
                </td>
                <td class="py-1 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
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

