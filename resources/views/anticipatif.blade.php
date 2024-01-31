<link rel="stylesheet" href="{{public_path('css.css')}}">

<div class="screen-full">

    <div class=" text-center">
        <img src="{{public_path('logo.png')}}">
        <h2 style="font-weight: bold">MILLE ET UNE MERVEILLE</h2>
    </div>
    
    <div class="text-center b-2 bg-gray-500 mb-2" style="font-weight: bold">RECU DE PAIEMENT ANTICIPATIF </div>
    
    <table class="w-full mb-2">
        <tr style="font-size: 1.1em; text-decoration:underline;" class="text-bold">
            <td>Occupation</td>
            <td class="text-r">Locataire</td>
        </tr>
        <tr>
            <td>
                Galerie {{ $records[0]->locataire->occupation->galerie->nom }}
            </td>
            <td class="text-r"> {{ $records[0]->locataire->noms }}</td>
        </tr>
        <tr class="">
            <td>Occupation {{ $records[0]->locataire->occupation->ref }} </td>
        </tr>
        <tr class="">
            <td>
                C/{{ $records[0]->locataire->occupation->galerie->commune->nom }},
                Av/{{ $records[0]->locataire->occupation->galerie->av }}, 
                N° {{ $records[0]->locataire->occupation->galerie->num }}
            </td>
        </tr>
    </table>
    
    <hr>
    
    <table class="w-full" id="t2">
        
            <tr style="background-color: #ababab95">
                <td>Mois</td>
                <td>Année</td>
                <td>Montant</td>
            </tr>
            @php
                $total = 0;
            @endphp
            @foreach ($records as $record)
            @php
                $total += $record->montant;
            @endphp
            <tr class="border-b">
                <td >{{ $record->mois }}</td>
                <td >{{ $record->annee }}</td>
                <td >{{ $record->montant }} $</td>
            </tr>
            @endforeach
            <tr class="text-lg font-bold bg-gray-50 border-b">
                <td class="" colspan="2">Total</td>
                <td>{{$total}} $</td>
            </tr>
        
    </table>
    
    @php
    $lelo = new DateTime('now');
    $lelo = $lelo->format('d-m-Y');
    @endphp
    
    <div class="w-full" style=" text-align:right; margin-top:30px;">
        <p>Aujourd'hui le, {{$lelo}}</p>
    </div>

</div>    
