<div>
@vite('resources/css/app.css')
    @php
        use Carbon\Carbon;
        use App\Models\Depense;
        $tot_depA = 0;
        $depensesA = Depense::whereDate('created_at', Carbon::yesterday())->get();
    @endphp
    @foreach ($depensesA as $dep)
        @php
            $tot_depA += $dep->sum('qte') * $dep->sum('cu');
        @endphp
    @endforeach
    {{-- Because she competes with no one, no one can compete with her. --}}
        {{-- <div class="flex justify-between">
            <h1 class="text-2xl font-bold mb:5">Grande caisse du {{Carbon::today()->format('d-m-Y')}}</h1>
        </div>
        <div style="" class="grid grid-cols-4 gap-20"> --}}


        {{-- <div style=" " class="p-4 rounded-lg bg-green-600 shadow-xl ">
            <b class="px-2 py-1 rounded-lg bg-white text-black shadow-lg" ></b>
            <span class="px-2 bg:red-600 text-lg font-bold text-white ">solde petite caisse</span>
            
            <div class="flex justify-center ">
                <p class="py-2 font-bold text-xl text-white">{{$loyersA->sum('montant') - $tot_depA}} $</p>
            </div>
        </div>
       
        <div style="" class="p-4 rounded-lg  bg-red-600 shadow-xl ">
            <b class="px-2 py-1 rounded-lg bg-white text-black shadow-lg" >{{$depenses->count()}}</b>
            <span class="px-2 bg:red-600 text-lg font-bold text-white">Depenses {{$depenses->count()}} </span>
            
            <div class="flex justify-center">
                @php
                    $total = 0;
                @endphp
                @foreach ($depenses as $depense)
                    @php
                        $total += $depenses->sum('qte') * $depenses->sum('cu')
                    @endphp
                @endforeach
                
                <p class="py-2 font-bold text-xl text-white">{{$total}}$</p>
            </div>
        </div> --}}
        {{-- <div style="" class="p-4 bg-blue-600 rounded-lg shadow-xl "> --}}
            {{-- <b class="px-2 py-1 rounded-lg bg-white text-black shadow-lg" ></b> --}}
            {{-- <span class="px-2 bg:red-600 text-lg font-bold text-white">Solde</span>
            
            <div class="flex justify-center ">
            <p class="py-2 font-bold text-xl text-white">{{$loyersA->sum('montant')- $total}}$</p>
            </div> --}}
        {{-- </div> --}}
    {{-- </div> --}}


    <h1 style=" color:white ; padding-left:15px; font-size:1.3em; backgound:blue; margin-top: 20px ; font-weight : bold; text-transform:uppercase " class="bg-blue-600">Grande caisse</h1>


    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'">
        
        <thead class="bg-gray-50 dark:bg-white/5">
            <tr class="text-lg font-bold" style="background-color:#abababc6;">
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Solde antérieur
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Somme petite caisse
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Depenses
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Solde actuel
                </td>
    
                {{-- <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
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
                </td> --}}
            </tr>

        </thead>

        <tbody class="divide-y divise-x divide-black whitespace-nowrap dark:divide-white/5">
                <tr class="border-b">
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">
                        {{$loyersA->sum('montant')}}$
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
                        {{$total}}$
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
                        {{$total}}$
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
                        {{$total}}$
                    </td>
                </tr>
        </tbody>
    </table>
</div>
