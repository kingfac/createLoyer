<link rel="stylesheet" href="{{public_path('css.css')}}"> 

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

<div class="text-center b-2 bg-gray mb-2">LISTE DES LOCATAIRES</div>

<table class="w-full mb-2">
    <tr style="font-size: 1.1em; text-decoration:underline;" class="text-bold">
        <td>Galerie: </td>
    </tr>
    
    <tr class="">
        <td>
            {{ $record[0]->occupation->galerie->nom }}
        </td>
    </tr>
</table>

<table class="w-full" id="t2">
    <thead>
        {{-- <tr class="bg-gray">
            <td colspan="4" class="text-center">Loyer du mois de {{ $record[0] }} / {{ $record[0] }}</td>
        </tr> --}}
    </thead>
    <thead>
        <tr>
            <td>ID</td>
            <td>NOM</td>
            <td>POST NON</td>
            <td>PRENOM</td>
            <td>TEL</td>
            <td>GARANTIE</td>

        </tr>
    </thead>
    <tbody class="py-2">
        @php
            $total = 0;
        @endphp

        @foreach ($record as $rec)    
        <tr>
            <td>{{ $loop->index + 1 }}</td>
            <td>{{ $rec->nom }}</td>
            <td>{{ $rec->postnom }} </td>
            <td>{{$rec->prenom}}</td>
            <td>{{$rec->tel}}</td>
            <td>{{$rec->garantie}}</td>

        </tr>
       
        @endforeach

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



