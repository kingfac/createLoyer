<link rel="stylesheet" href="{{public_path('css.css')}}"> 

<div class=" text-center">
    <img src="{{public_path('logo.png')}}">
    <h2>MILLE ET UNE MERVEILLE</h2>
</div>

<div class="text-center b-2 bg-gray-500 mb-2">RECU DE PAIEMENT</div>

<table class="w-full mb-2">
    <tr style="font-size: 1.1em; text-decoration:underline;" class="text-bold">
        <td>Occupation</td>
        <td class="text-r">Locataire</td>
    </tr>
    <tr>
        <td>
            Galerie {{ $record->locataire->occupation->galerie->nom }}
        </td>
        <td class="text-r"> {{ $record->locataire->noms }}</td>
    </tr>
    <tr class="">
        <td>Occupation {{ $record->locataire->occupation->ref }} </td>
    </tr>
    <tr class="">
        <td>
            C/{{ $record->locataire->occupation->galerie->commune->nom }},
            Av/{{ $record->locataire->occupation->galerie->av }}, 
            N° {{ $record->locataire->occupation->galerie->num }}
        </td>
    </tr>
</table>



<table class="w-full" id="t2">
    
        <tr >
            <td>Mois</td>
            <td >{{ $record->mois }}</td>
        </tr>
        <tr >
            <td>Année</td>
            <td >{{ $record->annee }}</td>
        </tr>
        <tr >
            <td>Montant</td>
            <td >{{ $record->montant }} $</td>
        </tr>
        <tr >
            <td >Type de payement</td>
            <td >

                @if ($record->garantie)
                    Avec Garantie
                @else
                    Sans Garantie
                @endif
            </td>
        </tr>
        <tr >
            <td>Date de payement</td>
            <td >{{ $record->created_at }}</td>
        </tr>
    
</table>



