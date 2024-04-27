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



