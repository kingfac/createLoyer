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

    <h1 style=" color:white ; padding-left:15px; font-size:1.3em; backgound:blue; margin-top: 20px ; font-weight : bold; text-transform:uppercase " class="bg-blue-600">Grande caisse</h1>


    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'">
        
        <thead class="bg-gray-50 dark:bg-white/5">
            <tr class="text-lg font-bold" style="background-color:#abababc6;">
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Solde antérieur
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Solde petite caisse
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Depenses
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Solde actuel
                </td>
            </tr>

        </thead>

        <tbody class="divide-y divise-x divide-black whitespace-nowrap dark:divide-white/5">
                <tr class="border-b">
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">
                        {{$soldeA}}$
                    </td>
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">
                        {{$soldepetite}}$
                    </td>
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">
                        {{$depenses}}$
                    </td>
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">
                        {{$soldeA+$soldepetite-$depenses}}$
                    </td>
                </tr>
        </tbody>
    </table>
</div>
