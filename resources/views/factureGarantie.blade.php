<style>
    .text-center {
        text-align: center;
    }
    .b-2{
        border:solid 2px
    }
    .b-1{
        border:solid 1px #ababab;
    }
    .bg-gray-500{
        background:#ababab;
    }
    .mb-2{
        margin-bottom: 20px;
    }
    .pb-2{
        padding-bottom:20px;
    }
    .pt-2{
        padding-top:20px;
    }
    .text-bold{
        font-weight: bold;
    }
    .py-2{
        padding-bottom:20px;
        padding-top:20px;
    }
    .w-full{
        width: 100%;
    }
    .text-r{
        text-align: right;
    }
    #t2 td{
        border:solid 1px #ababab;
    }
    #t2 tr{
        border:solid 1px #ababab;
    }

</style>

<div class=" text-center">
    <img src="https://static.vecteezy.com/ti/vecteur-libre/t2/620985-vecteur-de-modele-de-logos-maison-et-maison-gratuit-vectoriel.jpg">
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
    <tr >
        <td>Date de payement</td>
        <td >{{ $record->created_at }}</td>
    </tr>
    <tr >
        <td>Montant</td>
        <td >{{ $record->garantie }} $</td>
    </tr>       
    
</table>



