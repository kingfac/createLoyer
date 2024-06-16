<link rel="stylesheet" href="{{public_path('css.css')}}">
<div>

    <div class=" text-center">
        <table style=" width:100%; font-size: 1em; font-weight: bold; color:rgb(46, 131, 211)">
            <tr  style="">
                <td  style="">
        
                    <div class="text-start" style="">
                        <h2>MILLE ET UNE MERVEILLE</h2>
                        <h3>RCCM/15-B-9122</h3>
                        <h3>N.R.C. 53666 - Id. Nat. : 01-910-N 40270K</h3>
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
    
    <div class="text-center b-2 bg-gray mb-2">RECU DE PAIEMENT LOYER</div>
    
    <table class="w-full mb-2">
        <tr style="font-size: 1.1em; text-decoration:underline;" class="text-bold">
            <td>Occupation</td>
            <td class="text-r">Locataire</td>
        </tr>
        <tr>
            <td>
                Galerie {{ $record[0]->locataire->occupation->galerie->nom }}
            </td>
            <td class="text-r"> {{ $record[0]->locataire->noms }}</td>
        </tr>
        <tr class="">
            <td>Occupation {{ $record[0]->locataire->occupation->ref }} </td>
        </tr>
        <tr class="">
            <td>
                C/{{ $record[0]->locataire->occupation->galerie->commune->nom }},
                Av/{{ $record[0]->locataire->occupation->galerie->av }}, 
                N° {{ $record[0]->locataire->occupation->galerie->num }}
            </td>
        </tr>
    </table>
    
    <table class="w-full" id="t2">
        <thead>
            <tr class="bg-gray">
                <td colspan="4" class="text-center">Loyer du mois de {{ $record[0]->mois }} / {{ $record[0]->annee }}</td>
            </tr>
        </thead>
        <thead>
            <tr style="background-color:#abababc6;">
                <td>ID</td>
                <td>DATE PAIEMENT</td>
                <td>MONTANT</td>
                <td>TYPE PAIEMENT</td>
            </tr>
        </thead>
        <tbody class="py-2">
            @php
                $total = 0;
            @endphp
            @foreach ($record as $rec)    
            <tr class="border-b">
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $rec->created_at }}</td>
                <td class="bg-gray">{{ $rec->montant }} $</td>
                <td>
                    @if ($rec->garantie)
                        Avec Garantie
                    @else
                        Sans Garantie
                    @endif
                </td>
            </tr>
            @php
                $total += $rec->montant;
            @endphp
            @endforeach
            <tr >
                <td colspan="2">Total</td>
                <td class="bg-gray">{{$total}} $</td>
            </tr>
        </tbody>
    </table>
    
    
    
    {{-- <table class="w-full" id="t2">
        
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
        
    </table> --}}
    
    
    @php
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y');
    @endphp

    <div class="w-full" style=" text-align:right; margin-top:30px;">
        <p>Aujourd'hui le, {{$lelo}}</p>
    </div>
</div>    

