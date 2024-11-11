<link rel="stylesheet" href="{{public_path('css.css')}}">

<div class="w-screen">
    @php
        use App\Models\Loyer;
        use App\Models\User;
        use App\Models\Divers;
        use App\Models\Garantie;
        use Rmunate\Utilities\SpellNumber;
        use Illuminate\Support\Facades\Auth;


        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y').' à '.$lelo->format('H:i');

        $garanties = Garantie::where('locataire_id', $loc->id)->get();

    @endphp

    @forelse ($garanties as $garantie)
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
                    <h4 class="p-2 bg-gray-200" style="width: 100%;">Montant :  <b style="padding: 5px; background-color:rgb(98, 172, 241); width:100%;color:white">{{ $garantie->montant  }} $ </b></h4>
                    <h4>Payée le :  {{$garantie->created_at}}</h4>
                    
                    <h4>Intervenant : {{Auth::user()->name}}</h4>
                </td>     
            </tr>
        </table>


        <div class="text-center b-2 bg-gray-500 mb-2" style="font-size: 1em; color:rgb(46, 131, 211)">RECU GARANTIE N° {{ $garantie->id }}</div>

        <table class="w-full mb-2" style=" color:rgb(46, 131, 211); padding-bottom: 15px">
            <tr  class="text-bold">
                <td ><b>Recu de :</b> <span style=" text-decoration:underline;"><b>{{ $garantie->locataire->noms }}</b>, Galerie: <b>{{ $garantie->locataire->occupation->galerie->nom }}-{{$garantie->locataire->occupation->galerie->num}}</b></span></td>
                {{-- <td ></td> --}}
            </tr>
            <tr style="margin-top: 50px; padding-top:10px; width:20px; height:20" class=" py-6">
                <td><b>Somme de (en toutes lettres)</b> <b style="padding: 5px; background-color:gray; width:100%;"></b>  </td>    
            </tr>
            
            
            <tr style="font-size: 1.1em; " class="text-bold">
                <td colspan="" style="">
                    <div style="padding: 12px; background-color: rgb(135, 190, 241); width:95%">
                        <p style=" color:white; font-size:0.9em; text-transform: capitalize;">{{SpellNumber::value(intval($garantie->montant))->locale('fr')->toLetters()}} dollars américains.</p>
                    </div>
                    <b></b>
                </td>
                
            </tr>
            <tr style="">
                <td colspan="" style="padding: 5px; background-color:rgb(135, 190, 241; width:100%">
                    <b></b>
                </td>
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
    @empty
        <div style="font-size: 1.1em; " class="text-bold">
            <span colspan="" style="">
                <div style="padding: 12px; background-color: rgb(135, 190, 241); width:95%">
                    <p style=" color:white; font-size:0.9em; text-transform: capitalize;">Aucune garantie Enregistrée.</p>
                </div>
                <b></b>
            </span>
            
        </div>
    @endforelse

  
   
    
</div>