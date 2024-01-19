<link rel="stylesheet" href="{{public_path('css.css')}}"> 

<div class=" text-center">
    <img src="{{public_path('logo.png')}}">
    <h2>MILLE ET UNE MERVEILLE</h2>
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



