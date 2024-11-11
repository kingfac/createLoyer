<link rel="stylesheet" href="{{public_path('css.css')}}">


@php
use App\Models\User;
use Rmunate\Utilities\SpellNumber;
$lelo = new DateTime('now');
$lelo = $lelo->format('d-m-Y');
@endphp

<table style=" width:100%; font-size: 0.7em; font-weight: bold; color:rgb(46, 131, 211)">
    <tr  style="">
        <td  style="">

            <div class=" text-start" style="">
                {{-- <img src="{{public_path('logo.png')}}"> --}}
                <h1>MILLE ET UNE MERVEILLE</h1>
                <h2>RCCM/15-B-9122</h2>
                <h2>N.R.C. 53666 - Id. Nat. : 01-910-N 40270K</h2>
                <h2>Siège social av. Tshuapa N°90 C/Kinshasa</h2>
                <h2 style="borcer-bottom-width:100px;">Tel. : 0850758588 - 0816567028</h2>
            </div>  
        </td>
        <td style="text-align:right;" colspan="3">
            <h4>Kin, le {{$lelo}}</h4>
            <h4 class="p-2 bg-gray-200" style="width: 100%;">Montant :  <b style="padding: 5px; background-color:rgb(98, 172, 241); width:100%;color:white">{{ $records[0]->montant  }} $ </b></h4>
            <h4>Payé le :  {{$records[0]->created_at}}</h4>
            <h4>Loyer de :  {{$records[0]->mois}}-{{$records[0]->annee}}</h4>
            @php
                $nom = User::find($records[0]['users_id']);
            @endphp
            <h4>Intervenant : {{$nom->name}}</h4>
        </td>     
    </tr>
</table>
<div class="text-center b-2 bg-gray-500 mb-2" style="font-size: 1em; color:rgb(46, 131, 211)">RECU LOYER N° {{ $records[0]->id }}</div>

<table class="w-full mb-2" style=" color:rgb(46, 131, 211); padding-bottom: 15px">
    <tr  class="text-bold">
        <td ><b>Recu de :</b> <span style=" text-decoration:underline;"><b>{{ $records[0]->locataire->noms }}</b>, Galerie: <b>{{ $records[0]->locataire->occupation->galerie->nom }}-{{$records[0]->locataire->occupation->galerie->num}}</b></span></td>
        {{-- <td ></td> --}}
    </tr>
    <tr style="margin-top: 50px; padding-top:10px; width:20px; height:20" class=" py-6">
        <td><b>Somme de (en toutes lettres)</b> <b style="padding: 5px; background-color:gray; width:100%;"></b>  </td>    
    </tr>
    
    
    <tr style="font-size: 1.1em; " class="text-bold">
        <td colspan="" style="">
            <div style="padding: 12px; background-color: rgb(135, 190, 241); width:95%">
                <p style=" color:white; font-size:0.9em; text-transform: capitalize;">{{SpellNumber::value(intval($records[0]->montant))->locale('fr')->toLetters()}} dollars américains.</p>
            </div>
            <b></b>
        </td>
        
    </tr>
    <tr style="">
        <td colspan="" style="padding: 5px; background-color:rgb(135, 190, 241; width:100%">
            <b></b>
        </td>
    </tr>
    <tr>
        <td style="width:100%"><b>Pour :</b>{{$records[0]->observation ?? ' Loyer du mois de (d\') '.$records[0]->mois}}, 
        @if ($records[0]->garantie)
            Type de paiement : Avec garantie
        @else
            Type de paiement : Sans garantie
        @endif</td>
    </tr>
   
    <tr style="font-size: 0.7em">
        <td style="text-align:left;" colspan="3"><b>Visa Bailleur</b> </td>
        <td style="text-align:right;" colspan="3"><b>Visa Locataire</b> </td>        
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

<hr>

@for ($i = 1; $i < count($records); $i++)
    
    <table style=" width:100%; font-size: 0.7em; font-weight: bold; color:rgb(46, 131, 211); padding-top:10px">
        <tr  style="">
            <td  style="">

                <div class=" text-start" style="">
                    {{-- <img src="{{public_path('logo.png')}}"> --}}
                    <h1>MILLE ET UNE MERVEILLE</h1>
                    <h2>RCCM/15-B-9122</h2>
                    <h2>N.R.C. 53666 - Id. Nat. : 01-910-N 40270K</h2>
                    <h2>Siège social av. Tshuapa N°90 C/Kinshasa</h2>
                    <h2 style="borcer-bottom-width:100px;">Tel. : 0850758588 - 0816567028</h2>
                </div>
            </td>
            <td style="text-align:right;" colspan="3">
                <h4>Kin, le {{$lelo}}</h4>
                <h4 class="p-2 bg-gray-200" style="width: 100%;">Montant :  <b style="padding: 5px; background-color:rgb(98, 172, 241); width:100%;color:white">{{ $records[$i]->montant  }} $ </b></h4>
                <h4>Loyer de :  {{$records[$i]->mois}}-{{$records[$i]->annee}}</h4>
                @php
                    $nom = User::find($records[$i]['users_id']);
                @endphp
                <h4>Intervenant : {{$nom->name}}</h4>
            </td>     
        </tr>
    </table>
    <div class="text-center b-2 bg-gray-500 mb-2" style="font-size: 1em;padding:10px; color:rgb(46, 131, 211)">RECU LOYER, PAIEMENT ANTICIPATIF N° {{ $records[$i]->id }}</div>

    <table class="w-full mb-2" style="font-size: 0.7em; " style=" color:rgb(46, 131, 211)">
        <tr  class="text-bold">
            <td ><b>Recu de :</b> <span style=" text-decoration:underline;"><b>{{ $records[$i]->locataire->noms }}</b>, Galerie: <b>{{ $records[$i]->locataire->occupation->galerie->nom }}-{{$records[$i]->locataire->occupation->galerie->num}}</b></span></td>
            {{-- <td ></td> --}}
        </tr>
        <tr style="margin-top: 50px; padding-top:10px; width:20px; height:20" class=" py-6">
            <td><b>Somme de (en toutes lettres)</b> <b style="padding: 5px; background-color:gray; width:100%;"></b>  </td>    
        </tr>
        
        
        <tr style="font-size: 1.1em; " class="text-bold">
            <td colspan="" style="">
                <div style="padding: 12px; background-color: rgb(135, 190, 241); width:95%">
                    <p style=" color:white; font-size:0.9em; text-transform: capitalize;">{{SpellNumber::value(intval($records[$i]->montant))->locale('fr')->toLetters()}} dollars américains.</p>
                </div>
                <b></b>
            </td>
            
        </tr>
        <tr style="">
            <td colspan="" style="padding: 5px; background-color:rgb(135, 190, 241; width:100%">
                <b></b>
            </td>
        </tr>
        <tr>
            <td style="width:100%"><b>Pour :</b>{{$records[$i]->observation ?? ' Loyer du mois de (d\') '.$records[$i]->mois}}, 
            @if ($records[1]->garantie)
                Type de paiement : Avec garantie
            @else
                Type de paiement : Sans garantie
            @endif</td>
        </tr>
    
        <tr style="font-size: 0.7em">
            <td style="text-align:left;" colspan="3"><b>Visa Bailleur</b> </td>
            <td style="text-align:right;" colspan="3"><b>Visa Locataire</b> </td>        
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
    <hr>
@endfor






























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
