<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <div class="flex  justify-between">
        @php
            $lelo = new DateTime('now');
            $lelo = $lelo->format('d-m-Y');
        @endphp
        <h1 class="text-2xl font-bold" style="padding-bottom: 25px;">Paiement journalier du {{$lelo}}</h1>
        {{-- <x-filament::icon-button
            icon="heroicon-o-printer"
            tag="a"
            label="imprimer"
            tooltip="Imprimer"
            href="/storage/pdf/doc.pdf"
            target="_blank"
            
        /> --}}
    </div>
    {{-- {{$this->table}} --}}

    <link rel="stylesheet" href="{{public_path('css.css')}}"> 

<div class="">

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
            @if ($loyers->sum('montant') != 0 || $divers->sum('qte') != 0 || $garantie->sum('montant') != 0)
                
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
                            <p>{{$loyer->mois}}</p>
                            <p>({{$loyer->montant}}$, {{$admin->name}})</p>
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
        <tfoot class=" bg-gray-300">
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
</div>
