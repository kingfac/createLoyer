<link rel="stylesheet" href="{{public_path('css.css')}}">
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
<div  class="w-screen">

    <div class=" text-center">
        <img src="{{public_path('logo.png')}}">
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
            <tr style="background-color:#abababc6;">>
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
            <tr class="border-b">
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
    
    
    
    @php
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y');
    @endphp

    <div class="w-full" style=" text-align:right; margin-top:30px;">
        <p>Aujourd'hui le, {{$lelo}}</p>
    </div>
    

</div>    
