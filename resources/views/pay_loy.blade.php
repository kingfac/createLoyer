<link rel="stylesheet" href="{{public_path('css.css')}}"> 
@php
    use Rmunate\Utilities\SpellNumber;
    use App\Models\User;
    $lelo = new DateTime('now');
    $lelo = $lelo->format('d-m-Y').' à '.$lelo->format('H:i');
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
            <h4 class="p-2 bg-gray-200" style="width: 100%;">Montant :  <b style="padding: 5px; background-color:rgb(98, 172, 241); width:100%;color:white">{{ $record["montant"] }} $ </b></h4>
            <h4>Loyer de : {{$record['mois']}}-{{$record['annee']}}</h4>
            @php
                $nom = User::find($record['users_id']);
            @endphp
            <h4>Intervenant : {{$nom->name}}</h4>
        </td>     
    </tr>
</table>

<div class="text-center b-2 bg-gray-500 mb-2" style="font-size: 1em;padding:10px; color:rgb(46, 131, 211)">RECU LOYER N° {{$record["di"]}}</div>

<table class="w-full mb-2" style=" color:rgb(46, 131, 211)">
    <tr class="text-bold">
        @php
            use App\Models\Locataire;

            $locataire = Locataire::find($record["id"]);
        @endphp
        <td ><b>Recu de :</b> <span style=" text-decoration:underline;"><b>{{ $record["noms"] }}</b>, Galerie: <b>{{ $locataire->occupation->galerie->nom }}-{{$locataire->occupation->galerie->num}}</b></span></td>
        {{-- <td ></td> --}}
    </tr>
    <tr style="margin-top: 50px; padding-top:10px; width:20px; height:20" class=" py-6">
        <td><b>Somme de (en toutes lettres)</b> <b style="padding: 5px; background-color:gray; width:100%;"></b>  </td>    
    </tr>
    
    
    <tr class="text-bold">
        <td colspan="" style="">
            <div style="padding: 12px; background-color: rgb(135, 190, 241); width:95%">
                <p style=" color:white; font-size:0.9em; text-transform: capitalize;">{{SpellNumber::value(intval($record["montant"]))->locale('fr')->toLetters()}} dollars américains.</p>
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
        <td style="width:100%"><b>Pour :</b>{{$record["observation"] ?? ' Loyer du mois de (d\') '.$record["mois"]}}, 
        @if ($record["garantie"])
            Type de paiement : Avec garantie
        @else
            Type de paiement : Sans garantie
        @endif</td>
    </tr>
   
    <tr style="font-size: 0.7em">
        <td style="text-align:left;" colspan="3"><b>Visa Bailleur</b> </td>
        <td style="text-align:right;" colspan="3"><b>Visa Locataire</b> </td>        
    </tr>    
</table>



