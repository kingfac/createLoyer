    <link rel="stylesheet" href="{{public_path('css.css')}}"> 
    @php
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y').' à '.$lelo->format('H:i');
    @endphp
    <div class="font-serif" style="color:rgb(46, 131, 211)">
        <table style=" width:100%; font-size: 1em; font-weight: bold;" class="">
            <tr  style="">
                <td  style="">
    
                    <div class="text-start " style="">
                        {{-- <img src="{{public_path('logo.png')}}"> --}}
                        <h2>MILLE ET UNE MERVEILLE</h2>
                        <h3>N.R.C. 53666 - Id. Nat. : 01-910-N 40270 K</h3>
                        <h3>Av. Tshuapa N°90 C./Kinshasa</h3>
                        <h3 style="">Tel. : 0850758588 - 0816567028</h3>
                    </div>
                </td>
                <td style="text-align:right;" colspan="3">
                    <h4>Kin, le {{$lelo}}</h4>
                    {{-- <h4 class="p-2 bg-gray-200" style="width: 100%;">Montant :  <b style="padding: 5px; background-color:rgb(98, 172, 241); width:100%;color:white">{{ $record->montant }} $ </b></h4>
                    <h4>Loyer de : {{$record->mois}}-{{$record->annee}}</h4> --}}
                    <h4>Intervenant : {{Auth::user()->name}}</h4>
                </td>
            </tr>
        </table>
    
        <div class="text-center b-2 bg-gray-500 mb-2" style="font-weight:bold;padding:15px">{{$label}}</div>
    
        <hr>
        {{-- Nothing in the world is as soft and yielding as water. --}}
        <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'" style="color:rgb(46, 131, 211)">
            <tr>
                <td>
                    <h3>NOM DU LOCATAIRE</h3>
                </td>
                <td>
                    <h2>{{$locataire->noms}}</h2>
                </td>
            </tr>
            <tr>
                <td>
                    <h3>Galerie</h3>
                </td>
                <td>
                    <h3>Type occupation</h3>
                </td>
                <td>
                    <h3>Loyer à payer</h3>
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
           
            <h2>Paiement(s) effectué(s) au mois de {{$mois}} / {{$annee}}</h2>
        </div>
    
        <br>
        
            
        <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'" border="0.2">
            
            <thead class="bg-gray-50 dark:bg-white/5">
                <tr class="text-lg font-bold">
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        Date de paiement
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
       
        @php
            $lelo = new DateTime('now');
            $lelo = $lelo->format('d-m-Y');
        @endphp
    
        <div class="w-full" style=" text-align:right; margin-top:30px;">
            <p>Aujourd'hui le, {{$lelo}}</p>
        </div>
    </div>

