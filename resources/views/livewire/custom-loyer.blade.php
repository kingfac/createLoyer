<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    {{$this->form}}
    <br>
    @if (count($data) > 0)
        
    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'">
        
        <thead class="bg-gray-50 dark:bg-white/5">
            <tr class="text-lg font-bold">
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    E
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Nom
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Loyer à payer
                </td>

                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Loyer payé
                </td>

                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Reste
                </td>
            </tr>
        </thead>
    

        <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
        
            @php
                $_id = 0;
            @endphp
            @foreach ($data as $dt) 
            @if ($_id != $dt->id )
            @php
                $_id = $dt->id;
            @endphp
            <tr style=" padding-top:40px;padding-bottom:40px;">
                @if ($dt->somme == 0)
                    
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: red;">■</td>
                @else
                @if ($dt->occupation->montant == $dt->somme)
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">■</td>
                @else
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: blue;">■</td>       
                @endif 
                @endif
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->noms}}
                </td>
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->occupation->montant}}$
                </td>
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->somme ?? 0}} $
                </td>
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->occupation->montant - $dt->somme}} $
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>

    
    </table>
    @endif
</div>
