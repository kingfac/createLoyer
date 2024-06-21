<link rel="stylesheet" href="{{public_path('css.css')}}">

<div class="w-screen">
    @php
        use Carbon\Carbon;
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
    </div>
    
    <div class="text-center b-2 bg-gray-500 mb-2">{{$label}}</div>
    
    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'">
            
        <thead class="bg-gray-100 dark:bg-gray-700" style="background-color: rgb(218, 218, 218)">
            <tr>
                <th scope="col" class="py-1 px-0 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    N°
                </th>
                <th scope="col" class="py-1 px-0 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Locataire
                </th>
                <th scope="col" class="py-1 px-0 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Galerie
                </th>
                <th scope="col" class="py-1 px-0 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Type Occup
                </th>
                <th scope="col" class="py-1 px-0 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Loyer mensuel
                </th>
                <th scope="col" class="py-1 px-0 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Loyer payé
                </th>
                <th scope="col" class="py-1 px-0 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Reste
                </th>
            </tr>
        </thead>
    
    
    <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
       
        @php
            $_id = 0;
            $num = 1;
            $lp=0;
            $sm=0;
            $reste=0;
        @endphp
        @foreach ($data as $dt) 
        @if ($_id != $dt->id && $dt->somme == 0)
        @php
            $_id = $dt->id;
        @endphp
        <tr class="border-b">
            @if ($dt->somme == 0)
                
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: red;">{{$num}}</td>
            @else
               @if ($dt->occupation->montant == $dt->somme)
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">{{$num}}</td>
               @else
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: blue;">{{$num}}</td>       
               @endif 
            @endif
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                {{$dt->noms}}
            </td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                {{$dt->occupation->galerie->nom}}
            </td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                {{$dt->occupation->typeOccu->nom}}
            </td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                {{$dt->occupation->montant}}$
            </td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                {{$dt->somme ?? 0}} $
            </td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                {{$dt->occupation->montant - $dt->somme}} $
            </td>
            @php
                $lp+= $dt->occupation->montant;
                $sm+=$dt->somme;
                $reste+=$dt->occupation->montant - $dt->somme;
            @endphp
    
        </tr>

        @php
            $num+=1;
        @endphp
        @endif
        @endforeach
    </tbody>
    <tfoot style="background-color: rgb(218, 218, 218)">
        <tr class="border-b" >                
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="">Totaux</td>
            
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
            </td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
            </td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
            </td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                {{$lp}}$
            </td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                {{$sm}} $
            </td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                {{$reste}} $
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