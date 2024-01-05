{{-- @filamentStyles --}}
{{-- @vite('resources/css/app.css') --}}
<link rel="stylesheet" href="{{asset('build/assets/app-514a0b6d.css')}}">
<div class="w-screen bg-gray-100" style="width: 100vw;">
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <style>
        h1{
            font-size: 1.2em;
            font-weight: bold;
        }

        h2{
            
        }
        .flex div{
            display: flex;
            flex: auto;
            width: 100%;
        }
    </style>
    <div class="flex justify-between" >
        <h1>Situation personnelle du locataire</h1>
    </div>
    <hr>
    <table class="flex justify-between py-2" style="width: 100%;">
        <tr>
            <td>
                <h1>NOM DU LOCATAIRE</h1>
            </td>
            <td>
                <h2>{{$locataire->noms}}</h2>
            </td>
        </tr>
        <tr>
            <td>
                <h1>Galerie</h1>
            </td>
            <td>
                <h1>Type occupation</h1>
            </td>
            <td>
                <h1>Loyer à payer</h1>
            </td>
        </td>
        <tr>
            <td>
                <h2>{{$locataire->occupation->galerie->nom}}</h2>
            </td>
            <td>
                <h2>{{$locataire->occupation->typeOccu->nom}}</h2>
            </td>
            <td>
                <h2>{{$locataire->occupation->montant}} $</h2>
            </td>
        </tr>

    </table>
    <hr>
    <div class="py-2 flex justify-between items-center flex-col gap-2">
       
        <h1>Paiements effectués au mois de {{$mois}} / {{$annee}}</h1>
    </div>

    <br>
    @if (count($data) > 0)
        
    <table class="w-full" style="width: 100%;" border="1">
        
        <thead class="bg-gray-50 dark:bg-white/5">
            <tr class="text-lg font-bold">
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Date
                </td>

                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Loyer payé
                </td>

                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Reste
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Observation
                </td>
               
            </tr>
        </thead>
    

        <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
        
            @php
                $_id = 0;
                $total = 0;
            @endphp
            @foreach ($data as $ly) 
            @php
                $total += $ly->montant;
            @endphp
            <tr class="hover:bg-white/5 dark:hover:bg-white/5">
                
                
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$ly->date_loyer}}
                </td>
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$ly->montant ?? 0}} $
                </td>
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$ly->occupation->montant - $total}} $
                </td>

                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$ly->observation ?? 'Aucune observation'}} 
                </td>

               
                
            </tr>
            @endforeach
            <tr class="text-lg font-bold bg-gray-50">
                <td class="">Total</td>
                <td>{{$total}} $</td>
            </tr>
        </tbody>

    
    </table>
    @else
    <div class="flex justify-center items-center" style="padding: 100px;">
        <h1>Aucun paiement effectué au cours de ce mois-ci !</h1>
    </div>
    @endif
    
</div>
