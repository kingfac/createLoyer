<link rel="stylesheet" href="{{public_path('css.css')}}"> 

<div class="w-screen">
    @php
        use Carbon\Carbon;
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
    @php
        use App\Models\Loyer;
        use App\Models\User;
        use App\Models\Divers;
        use App\Models\Garantie;
    @endphp
    
    
    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'">
            
        <thead class="bg-gray-50 dark:bg-white/5">
            <tr class="text-lg font-bold" style="background-color:#abababc6;">
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    N°
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Locataire
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Galerie
                </td>
    
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Occupation
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Loyer
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Garantie
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Divers
                </td>
            </tr>
        </thead>
        <tbody class="divide-y divise-x divide-black whitespace-nowrap dark:divide-white/5">
            @php
                $num = 1;
                $somme_loyer = 0;
                $somme_garantie = 0;
                $somme_divers = 0;
            @endphp
            @foreach ($locs as $loc) 
            
            @php
                $loyers = Loyer::where('locataire_id',$loc->id)->whereRaw("DAY(created_at) = DAY(NOW())")->get();
                $divers = Divers::where('locataire_id', $loc->id)->whereRaw("DAY(created_at) = DAY(NOW())")->get();
                $garanties = Garantie::where('locataire_id', $loc->id)->whereRaw("DAY(created_at) = DAY(NOW())")->get();
            @endphp
            @if ($loyers->sum('montant') != 0 || $divers->sum('qte') != 0 || $garanties->sum('montant') != 0)
                
                <tr class="border-b">
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">{{$num}}</td>
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        {{$loc->noms}}
                        
                    </td>
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        {{$loc->occupation->galerie->nom}}-{{$loc->occupation->galerie->num}}
                    </td>
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        {{$loc->occupation->typeOccu->nom}}
                    </td>
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 text-small last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        @forelse ($loyers as $loyer)
                            @php
                                $admin = User::find($loyer->users_id);
                                $somme_loyer += $loyer->montant;
                            @endphp
                            <p>{{$loyer->mois}}({{$loyer->montant}}$, {{$admin->name}})</p>
                            {{-- <p></p> --}}
                        @empty
                            <p>-----------</p>
                        @endforelse
                    
                    </td>
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                       
                        @forelse ($garanties as $garantie)
                            @php
                                $admin1 = User::find($garantie->users_id);
                                $somme_garantie += $garantie->montant
                            @endphp
                            <p>{{$garantie->montant}} $ ({{$admin1->name}})</p>
                        @empty
                            <p>-----------</p>
                        @endforelse
                    </td>
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        @forelse ($divers as $diver)
                            @php
                                $admin2 = User::find($diver->users_id);
                                $somme_divers = $diver->qte*$diver->cu
                            @endphp
                            <p>{{$diver->qte*$diver->cu}} $ ({{$admin2->name}})</p>
                        @empty
                            <p>-----------</p>
                        @endforelse
                        {{-- {{$l->created_at}}  --}}
                    </td>
                    
            
                </tr>
                @php
                    $num+=1;
                @endphp
            @endif
            @endforeach
        </tbody>
        <tfoot>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">Totaux</td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;"></td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;"></td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;"></td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">{{$somme_loyer}}$</td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">{{$somme_garantie}}$</td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">{{$somme_divers}}$</td>
            
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