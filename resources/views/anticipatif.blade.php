<link rel="stylesheet" href="{{public_path('css.css')}}">


@php
$lelo = new DateTime('now');
$lelo = $lelo->format('d-m-Y');
@endphp
<table style=" width:100%; font-size: 1em; font-weight: bold; color:rgb(46, 131, 211)">
    <tr  style="">
        <td  style="">

            <div class=" text-center" style="">
                {{-- <img src="{{public_path('logo.png')}}"> --}}
                <h2>MILLE ET UNE MERVEILLE</h2>
                <h3>N.R.C. 53666 - Id. Nat. : 01-910-N 40270 K</h3>
                <h3>Av. Tshuapa N°90 C./Kinshasa</h3>
                <h3 style=" border-bottom:solid 1px; borcer-bottom-width:100px;">Tel. : 0850758588 - 0816567028</h3>
            </div>
        </td>
        <td style="text-align:right;" colspan="3">
            <h4>Kin, le {{$lelo}}</h4>
            <h4 class="p-2 bg-gray-200" style="width: 100%;">Montant :  <b style="padding: 5px; background-color:rgb(98, 172, 241); width:100%;color:white">{{ $records[0]->montant }} $ </b></h4>
            <h4>Loyer de : {{$records[0]->mois}}-{{$records[0]->annee}}</h4>
        </td>
        
    </tr>
</table>
<div class="text-center b-2 bg-gray-500 mb-2" style="font-size: 2em;padding:10px; color:rgb(46, 131, 211)">RECU LOYER, PAIEMENT ANTICIPATIF N° {{ $records[0]->id }}</div>

<table class="w-full mb-2" style=" color:rgb(46, 131, 211)">
    <tr style="font-size: 1.1em; " class="text-bold">
        <td ><b>Recu de :</b> <span style=" text-decoration:underline;"><b>{{ $records[0]->locataire->noms }}</b>, Galerie: <b>{{ $records[0]->locataire->occupation->galerie->nom }}-{{$records[0]->locataire->occupation->galerie->num}}</b></span></td>
        {{-- <td ></td> --}}
    </tr>
    <tr style="margin-top: 50px; padding-top:10px; width:20px; height:20" class=" py-6">
        <td><b>Somme de (en toutes lettres)</b> <b style="padding: 5px; background-color:gray; width:100%;"></b>  </td>    
    </tr>
    
    {{-- <tr>
        <td colspan="1">Pour : </td>
        <td colspan="3" style="padding: 5px; background-color:gray; width:100%">
            <b ></b> 
        </td>
        
    </tr> --}}
    <tr style="">
        <td colspan="" style="">
            <div style="padding: 12px; background-color: rgb(135, 190, 241); width:95%">

            </div>
            <div style="padding: 12px; background-color: rgb(135, 190, 241); width:95%">

            </div>
            <b></b>
        </td>
        
    </tr>
    <tr style="">
        <td colspan="" style="padding: 12px; background-color:rgb(135, 190, 241; width:100%">
            <b></b>
        </td>
        
    </tr>
    <tr>
        <td style=" width:100%"><b>Pour :</b> ............................................................................................................................................</td>
    </tr>
    <tr>
        <td style=" width:100%">........................................................................................................................................................</td>
    </tr>
    <tr>
        <td><b>Visa Bailleur</b> </td>
        <td style=" width:95%; padding-right: 30px"><b>Visa Locataire</b> </td>
        
        {{-- <span>Visa Locataire</span> --}}
    </tr>
    
   {{--  <tr class="">
        <td>Occupation {{ $record->locataire->occupation->ref }} </td>
    </tr>
    <tr class="">
        <td>
            C/{{ $record->locataire->occupation->galerie->commune->nom }},
            Av/{{ $record->locataire->occupation->galerie->av }}, 
            N° {{ $record->locataire->occupation->galerie->num }}
        </td>
    </tr> --}}
</table>

































{{-- 


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

</div>     --}}
