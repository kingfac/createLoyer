<link rel="stylesheet" href="{{public_path('css.css')}}"> 
<div class=" text-center">
    <img src="{{public_path('logo.png')}}">
    <h2>MILLE ET UNE MERVEILLE</h2>
</div>

<div class="text-center b-2 bg-gray-500 mb-2">Facture de restitution de garantie</div>


    {{-- Nothing in the world is as soft and yielding as water. --}}
    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'">
        <tr>
            <td>
                <h4>NOM DU LOCATAIRE</h4>
            </td>
            <td colspan="4">
                <h4>{{$data[0]->locataire->noms}}</h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>Galerie</h4>
            </td>
            <td>
                <h4>Type occupation</h4>
            </td>
            <td>
                <h4>Loyer</h4>
            </td>
            <td>
                <h4> Garanties utilisées</h4>
            </td>
            <td>
                <h4>Montant à restituer</h4>
            </td>
        <tr>
            <td>
                <h4>{{$data[0]->locataire->occupation->galerie->nom}}</h4>
            </td>
            <td>
                <h4>{{$data[0]->locataire->occupation->typeOccu->nom}}</h4>
            </td>
            <td>
                <h4>{{$data[0]->locataire->occupation->montant}} $</h4>
            </td>
            <td>
                <h4>{{$loyers}} $</h4>
            </td>
            <td>
                <h4>   {{$data[$data->count()-1]->montant}} $</h4>
            </td>
        </tr>

    </table>
    <hr>
    <div class="py-2 flex justify-between items-center flex-col gap-2">
       
        {{-- <h4>Paiements effectués au mois de {{$mois}} / {{$annee}}</h4> --}}
    </div>

    <br>
    <h3>Garanties payées</h3>
    <hr>
    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'" border="0.2">
        
        <thead class="bg-gray-50 dark:bg-white/5">
            <tr class="text-lg font-bold" style="background-color: #ababab6f">
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Date
                </td>

                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Garanties payées
                </td>
            </tr>
        </thead>
    

        <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
        
            @php
                $total = 0;
            @endphp
            @foreach ($data as $ly)
            @if ($ly->restitution == false)
                @php
                    $total += $ly->montant;
                @endphp
                <tr class="hover:bg-white/5 dark:hover:bg-white/5 border-b">
                    
                    
                    <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        {{$ly->created_at}}
                    </td>
                    <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        {{$ly->montant ?? 0}} $
                    </td>   
                </tr>
            @endif
            @endforeach
            <tr class="text-lg font-bold bg-gray-50">
                <td class="">Total</td>
                <td>{{$total}} $</td>
            </tr>
                
        </tbody>

    
    </table>

    @php
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y');
    @endphp

    <div class="w-full" style=" text-align:right; margin-top:30px;">
        <p>Aujourd'hui le, {{$lelo}}</p>
    </div>

   
  

