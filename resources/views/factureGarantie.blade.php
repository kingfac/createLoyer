<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{public_path('css.css')}}">
</head>
<body class="w-screen">
    
    <div class=" text-center">
        <img src="{{public_path('logo.png')}}">
        <h2>MILLE ET UNE MERVEILLE</h2>
    </div>
    
    <div class="text-center b-2 bg-gray-500 mb-2">RECU DE PAIEMENT GARANTIE</div>
    
    <table class="w-full mb-2">
        <tr style="font-size: 1.1em; text-decoration:underline;" class="text-bold">
            <td>Occupation</td>
            <td class="text-r">Locataire</td>
        </tr>
        <tr>
            <td>
                Galerie {{ $record->occupation->galerie->nom }}
            </td>
            <td class="text-r"> {{ $record->noms }}</td>
        </tr>
        <tr class="">
            <td>Occupation {{ $record->occupation->ref }} </td>
        </tr>
        <tr class="">
            <td>
                C/{{ $record->occupation->galerie->commune->nom }},
                Av/{{ $record->occupation->galerie->av }}, 
                N° {{ $record->occupation->galerie->num }}
            </td>
        </tr>
    </table>
    
    
    
    <table class="w-full" id="t2">
        <tr class="text-lg font-bold " style="background-color:#abababc6;">
            <td>Date de payement</td>
            <td >{{ $record->created_at }}</td>
        </tr>
        <tr >
            <td>Montant</td>
            <td >{{ $record->garantie }} $</td>
        </tr>       
        
    </table>
    
    

</body>    
</html>



