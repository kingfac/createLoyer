<link rel="stylesheet" href="{{public_path('css.css')}}"> 
<div class=" text-center">
    @php
$lelo = new DateTime('now');
$lelo = $lelo->format('d-m-Y');
@endphp
<div class=" text-center w-full">
    @php
        use Carbon\Carbon;
        use Illuminate\Support\Facades\Auth;
        use Rmunate\Utilities\SpellNumber;


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
                {{-- <h4 class="p-2 bg-gray-200" style="width: 100%;">Montant :  <b style="padding: 5px; background-color:rgb(98, 172, 241); width:100%;color:white">{{ $records[0]->montant  }} $ </b></h4> --}}
                <h4>Garantie utilisée :  {{$loyers}}$</h4>
                <h4>Montant à restituer :  {{$data[$data->count()-1]->montant}}$</h4>
                <h4>Intervenant : {{Auth::user()->name}}</h4>
            </td>     
        </tr>
    </table>
</div>
</div>

<table class="w-full mb-2" style=" color:rgb(46, 131, 211); padding-bottom: 15px">
    <tr  class="text-bold">
        <td ><b>Recu de :</b> <span style=" text-decoration:underline;"><b>{{ $data[0]->locataire->noms }}</b>, Galerie: <b>{{ $data[0]->locataire->occupation->galerie->nom }}-{{$data[0]->locataire->occupation->galerie->num}}</b></span></td>
        {{-- <td ></td> --}}
    </tr>
    <tr style="margin-top: 50px; padding-top:10px; width:20px; height:20" class=" py-6">
        <td><b>Somme de (en toutes lettres)</b> <b style="padding: 5px; background-color:gray; width:100%;"></b>  </td>    
    </tr>
    
    
    <tr style="font-size: 1.1em; " class="text-bold">
        <td colspan="" style="">
            <div style="padding: 12px; background-color: rgb(135, 190, 241); width:95%">
                <p style=" color:white; font-size:0.9em; text-transform: capitalize;">{{SpellNumber::value(intval($data[$data->count()-1]->montant))->locale('fr')->toLetters()}} dollars américains.</p>
            </div>
            <b></b>
        </td>
        
    </tr>
    <tr style="">
        <td colspan="" style="padding: 5px; background-color:rgb(135, 190, 241; width:100%">
            <b></b>
        </td>
    </tr>
    
   
    <h3>Garanties payées</h3>
    <hr>
    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'" border="0.2">
        
        <thead class="bg-gray-50 dark:bg-white/5">
            <tr class="text-lg font-bold" style="background-color: #ababab6f">
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Date
                </td>
    
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Montant
                </td>
            </tr>
        </thead>
    
    
        <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
        
            @php
                $total = 0;
            @endphp
            @foreach ($data as $ly)
            @if ($ly->restitution == false)
                @php
                    $total += $ly->montant;
                @endphp
                <tr class="hover:bg-white/5 dark:hover:bg-white/5 border-b">
                    
                    
                    <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        {{$ly->created_at->format('d-m-Y à H:i ')}}
                    </td>
                    <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        {{$ly->montant ?? 0}} $
                    </td>   
                </tr>
            @endif
            @endforeach
            <tr class="text-lg font-bold bg-gray-50">
                <td class="">Total</td>
                <td>{{$total}} $</td>
            </tr>
                
        </tbody>
    
    
    </table>
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


