<link rel="stylesheet" href="{{public_path('css.css')}}"> 

<div class="w-screen">
    @php
        use App\Models\Loyer;
        use App\Models\User;
        use App\Models\Divers;
        use App\Models\Garantie;
        use Rmunate\Utilities\SpellNumber;

        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y').' à '.$lelo->format('H:i');
    @endphp
    <table style=" width:100%; font-size: 1em; font-weight: bold; color:rgb(46, 131, 211)">
        <tr  style="">
            <td  style="">
    
                <div class="text-start" style="">
                    <h2>MILLE ET UNE MERVEILLE</h2>
                    <h3>N.R.C. 53666 - Id. Nat. : 01-910-N 40270 K</h3>
                    <h3>Av. Tshuapa N°90 C./Kinshasa</h3>
                    <h3 style=" border-bottom:solid 1px; borcer-bottom-width:100px;">Tel. : 0850758588 - 0816567028</h3>
                </div>
            </td>
            <td style="text-align:right;" colspan="3">
                <h4>Kin, le {{$lelo}}</h4>
            </td>
        </tr>
    </table>
    <div class="text-center b-2 bg-gray-500 mb-2" style="font-size: 2em;padding:10px; color:rgb(46, 131, 211);text-transform:uppercase">{{ $label }}</div>
    
    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'" style="color:rgb(46, 131, 211)">
            
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
                    Garantie payée
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Garantie utilisée
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Reste
                </td>
            </tr>
        </thead>
        <tbody class="divide-y divise-x divide-black whitespace-nowrap dark:divide-white/5">
            @php
                $num = 1;
                $somme_garantie = 0;
            @endphp
            
            @php
                $garanties = Garantie::where('locataire_id', $loc->id)->get();
            @endphp
            @if ($garanties->sum('montant') != 0 )
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
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                       
                        @forelse ($garanties as $garantie)
                            @php
                                $admin1 = User::find($garantie->users_id);
                                $somme_garantie += $garantie->montant
                            @endphp
                            <p>{{$somme_garantie}} $</p>
                        @empty
                            <p>-----------</p>
                        @endforelse
                    </td>
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                       
                            @php
                                $utilisee = Loyer::where(['locataire_id'=> $loc->id,'garantie'=>true])->sum('montant');
                            @endphp
                            <p>{{$utilisee}} $</p>
                    </td>
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                       {{$restitution}} $
                    </td>
                </tr>
                @php
                    $num+=1;
                @endphp
            @endif
        </tbody>
        {{-- <tfoot>
            <td class="py-5 fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color:rgb(46, 131, 211);" colspan="4">Total</td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color:rgb(46, 131, 211);font-weight:bold">{{$somme_garantie}}$</td>
        </tfoot> --}}
    </table>
    @php
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y');
    @endphp

    <div class="w-full" style=" text-align:right; margin-top:30px;color:rgb(46, 131, 211);">
        <p>Aujourd'hui le, {{$lelo}}</p>
    </div>
</div>