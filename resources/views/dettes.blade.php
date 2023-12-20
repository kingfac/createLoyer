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
    .bg-gray{
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

@php
    use App\Models\Loyer;
    use App\Models\Locataire;

    $dettes = [];
    $locs = Locataire::all();
    
    foreach ($locs as $loc) {
            $l = Loyer::where('mois',$mois)->where('locataire_id',$loc['id'])->get();
            if ($l->count() > 0 ){
                $m = $l[0]->locataire->occupation->montant;

                if ($l->count() > 1) {
                    // dd('plusieurs tranches');
                    $somme = $l->sum('montant');
                    if($somme == $m){

                        // dd('pas de dette');
                    }
                    if ($somme < $m) {
                        // dd('dette1');
                        array_push($dettes, ['loc' => $loc,'dette' => ($m-$somme)]);
                    }
                        
                }
            }

            elseif($l->count() == 0) {
                array_push($dettes, ['loc' => $loc ,'dette' => ($loc->occupation->montant)]);
            }
            
    } 
    // dd($dettes);

@endphp

<div class=" text-center">
    <img src="https://static.vecteezy.com/ti/vecteur-libre/t2/620985-vecteur-de-modele-de-logos-maison-et-maison-gratuit-vectoriel.jpg">
    <h2>MILLE ET UNE MERVEILLE</h2>
</div>

<div class="text-center b-2 bg-gray mb-2">LISTE DES LOCATAIRES AVEC DETTES</div>
<table class="w-full mb-2">
    <tr style="font-size: 1.1em; text-decoration:underline;" class="text-bold">
        <td>Mois/Année </td>
    </tr>
    
    <tr class="">
       <td>{{$mois}}/{{$annee}}</td>
    </tr>
    
</table>

<table class="w-full" id="t2">
    <thead>
        
    </thead>
    <thead>
        <tr>
            <td>ID</td>
            <td>NOM</td>
            <td>POST NON</td>
            <td>PRENOM</td>
            <td>TEL</td>
            <td>GARANTIE($)</td>
            <td>DETTE($)</td>

        </tr>
    </thead>
    <tbody class="py-2">

        @foreach ($dettes as $dette)    
        <tr>
            <td>{{ $loop->index + 1 }}</td>
            <td>{{ $dette["loc"]->nom }}</td>
            <td>{{ $dette["loc"]->postnom }} </td>
            <td>{{$dette["loc"]->prenom}}</td>
            <td>{{$dette["loc"]->tel}}</td>
            <td>{{$dette["loc"]->garantie}}</td>
            <td>{{$dette["dette"]}}</td>

        </tr>
       
        @endforeach

    </tbody>
</table>






