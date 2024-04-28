<div>
    @vite('resources/css/app.css')
        @php
            use Carbon\Carbon;
        @endphp
        {{-- Because she competes with no one, no one can compete with her. --}}
            <div class="flex justify-between mb-10">
                <h1 class="text-2xl font-bold mb:5">Petite caisse du {{Carbon::today()->format('d-m-Y')}}</h1>
                <x-filament::icon-button
            icon="heroicon-o-printer"
            tag="a"
            label="imprimer"
            tooltip="Imprimer"
            wire:click="imprimer"
            target="_blank"
            
        />
            </div>
        
        <div class=" flex justify-between"> 
            <h1 style=" color:white ;width:100%; padding-left:15px; font-size:1.3em; backgound:blue; margin-top: 20px ; font-weight : bold; text-transform:uppercase " class="bg-blue-600">Petite caisse</h1>
           
        </div>

        <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'">
        
            <thead class="bg-gray-50 dark:bg-white/5">
                <tr class="text-lg font-bold" style="background-color:#abababc6;">
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        Loyers
                    </td>
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        Garanties
                    </td>
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        Divers
                    </td>
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        Solde petite caisse
                    </td>
                </tr>

            </thead>

            <tbody class="divide-y divise-x divide-black whitespace-nowrap dark:divide-white/5">
                    <tr class="border-b">
                        <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">
                            {{$loyers->sum('montant')}}$
                        </td>
                        <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($depenses as $depense)
                                @php
                                    $total += $depenses->sum('qte') * $depenses->sum('cu')
                                @endphp
                            @endforeach
                            {{$garanties}}$
                        </td>
                        <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">
                            {{$total}}$
                        </td>
                        <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">
                            {{$total+$garanties+$loyers->sum('montant')}}$
                        </td>
                    </tr>
            </tbody>
        </table>
    </div>
    