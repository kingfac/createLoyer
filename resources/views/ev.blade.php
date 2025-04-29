<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('build/assets/app-3e76f9e4.css') }}">
    {{-- <link rel="stylesheet" href="{{public_path('css.css')}}"> --}}
</head>
<body class="w-screen">
    @php
        use Carbon\Carbon;
    @endphp
    <div class=" text-center w-full">
        
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
    
    <div class="text-center b-2 bg-blue-500 mb-2">{{$label}}</div>
    
    <table class="w-full table-auto divide-y divide-gray-200 text-start">
            
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
            $tot_lp=0;
            $tot_somme=0;
            $tot_reste=0;
            $num=1;

        @endphp
        @foreach ($data as $dt) 
        @if ($_id != $dt->id )
        @php
            $_id = $dt->id;
        @endphp
        <tr class="border-b">
                <td class="fi-ta-cell px-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" >{{$num}}</td>
                @php
                    $num +=1;
                @endphp
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
                    @php
                        $tot_lp += $dt->occupation->montant;
                    @endphp
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->somme ?? 0}} $
                    @php
                        $tot_somme += $dt->somme;
                    @endphp
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->occupation->montant - $dt->somme}} $
                    @php
                        $tot_reste += $dt->occupation->montant - $dt->somme;
                    @endphp
                </td>
                
    
        </tr>
        @endif
        @endforeach
        <tfoot style="background-color: rgb(218, 218, 218)">
            <tr class="border-b">
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="">Totaux</td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$tot_lp}} $
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$tot_somme}} $
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$tot_reste}} $
                </td>
            </tr>
        </tfoot>
    </tbody>
    
    
    </table>

    @php
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y');
    @endphp

    <div class="w-full" style=" text-align:right; margin-top:30px;">
        <p>Aujourd'hui le, {{$lelo}}</p>
    </div>
</body>
</html>