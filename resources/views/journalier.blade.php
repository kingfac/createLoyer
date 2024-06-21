<link rel="stylesheet" href="{{public_path('css.css')}}"> 

<div class="w-screen">
    @php
        use Carbon\Carbon;
        use App\Models\Loyer;
        use App\Models\Divers;
        $tot_div = 0;
    @endphp

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
        <h2>{{$label}}</h2>
    </div>
    
    
    
    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5" >
            
        <thead class="bg-gray-50 dark:bg-white/5" style="background-color:#abababc6;">
            <tr class="text-lg font-bold" >
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    N°
                </td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Nom
                </td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Galerie
                </td>
    
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Occupation
                </td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    mois
                </td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Loyer
                </td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Dettes
                </td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Divers
                </td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Date
                </td>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5" >
            @php
                $num = 1;
                $tot_montant=0;
            @endphp
            @foreach ($data as $dt) 
            
        
            <tr class="border-b">
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">{{$num}}</td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->locataire->noms}}
                </td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->locataire->occupation->galerie->nom}}-{{$dt->locataire->occupation->galerie->num}}
                </td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->locataire->occupation->typeOccu->nom}}
                </td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->mois}}
                </td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->montant}} $
                    @php
                        $tot_montant+=$dt->montant;
                    @endphp
                </td>
                <td>
                    @php
                        $sommesloyerPayer = Loyer::where('locataire_id', $dt->locataire_id)
                                                  ->where('mois', $dt->mois)
                                                  ->where('created_at', '<=', $dt->created_at)
                                                  ->sum('montant');

                        $montantAPayer = $dt->locataire->occupation->montant;
                    @endphp
                    {{$montantAPayer - $sommesloyerPayer}} $
                </td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    @php
                        $current_date = NOW()->format('Y-m-d');
                        $divers = Divers::where('locataire_id',$dt->locataire_id)
                                            ->whereRaw(" DATE(created_at) = '$current_date' ")
                                            ->sum('total');

                        $tot_div += $divers;

                    @endphp
                    {{$divers}} $
                </td>

                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->created_at}} 
                </td>
                
        
            </tr>
            @php
                $num+=1;
            @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr class="border-b " style="background-color: rgb(230, 230, 230)">
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">Totaux</td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;"></td>

                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;"></td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                </td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                </td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$tot_montant}} $    
                </td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    @php
                        $sommeDette = 0;
                        $sommesLoyerPaye = 0;
                        $dette = 0;

                        // Tableau pour stocker les locataires et les mois déjà traités
                        $dejaTraites = [];

                        foreach ($data as $value) {
                            // Crée une clé unique pour chaque combinaison locataire_id et mois
                            $cleUnique = $value->locataire_id . '-' . $value->mois;

                            // Vérifie si cette combinaison a déjà été traitée
                            if (!isset($dejaTraites[$cleUnique])) {
                                // Marque cette combinaison comme traitée
                                $dejaTraites[$cleUnique] = true;
                                $sommesLoyerPaye = Loyer::where('locataire_id', $value->locataire_id)
                                ->where('mois', $value->mois)       
                                ->sum('montant');

                                $dette = $value->locataire->occupation->montant - $sommesLoyerPaye;
                                $sommeDette += $dette;
                            }
                        }

                    @endphp
                    {{$sommeDette}}$
                </td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$tot_div}} $
                </td>
                <td class="fi-ta-cell px-3 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                </td>
                
        
            </tr>
        </tfoot>
    </table>
    @php
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y');
    @endphp

    <div class="w-full" style=" text-align:right; margin-top:30px;">
        <p>Aujourd'hui le, {{$lelo}}</p>
    </div>
</div>