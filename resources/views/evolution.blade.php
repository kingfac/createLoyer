<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    {{-- <link rel="stylesheet" href="{{asset('build/assets/app-2bf04d98.css') }}"> --}}
    <link rel="stylesheet" href="{{public_path('css.css')}}">
</head>
<body class="w-screen">
    
    <div class=" text-center w-full">
        
        <img src="{{public_path('logo.png')}}">
        <h2 class="text-bold text-xl">MILLE ET UNE MERVEILLE</h2>
    </div>
    
    <div class="text-center b-2 bg-blue-500 mb-2">{{$label}}</div>
    
    <table class="w-full table-auto divide-y divide-gray-200 text-start">
            
        <thead class="bg-gray-50 dark:bg-white/5">
            <tr class="text-lg font-bold " style="background-color:#abababc6;">
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    E
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Nom
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Loyer à payer
                </td>
    
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Loyer payé
                </td>
    
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Reste
                </td>
            </tr>
        </thead>
    
    
    <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
       
        @php
            $_id = 0;
        @endphp
        @foreach ($data as $dt) 
        @if ($_id != $dt->id )
        @php
            $_id = $dt->id;
        @endphp
        <tr class="border-b">
            @if ($dt->somme == 0)
                
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: red;">#</td>
            @else
               @if ($dt->occupation->montant == $dt->somme)
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">#</td>
               @else
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: blue;">#</td>       
               @endif 
            @endif
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                {{$dt->noms}}
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
    
        </tr>
        @endif
        @endforeach
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