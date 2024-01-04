<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    
    <div class="flex justify-between">
        <h1 class="text-2xl font-bold" style="padding-bottom: 25px;">Locataire avec paiement partiel du mois de : {{ $mois }}</h1>
        {{--  {{ $this->form }}
        {{ $this->table }} --}}
        <x-filament::icon-button
            icon="heroicon-o-printer"
            tag="a"
            label="imprimer"
            tooltip="Imprimer"
            href="/pdf/doc.pdf"
            target="_blank"
                
        />
    </div>
   {{--  {{ $this->form }}
    {{ $this->table }} --}}

    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'">
        
            <thead class="bg-gray-50 dark:bg-white/5">
                <tr class="text-lg font-bold">
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        id
                    </td>
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        Nom
                    </td>
                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        Garantie
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
            @if ($_id != $dt->id && $dt->somme < $dt->occupation->montant && $dt->somme > 0)
            @php
                $_id = $dt->id;
            @endphp
            <tr>       
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$loop->index + 1}}
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->noms}}
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->montant}}
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->occupation->montant}}
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->somme}}
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->occupation->montant - $dt->somme}}
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>

       
    </table>

</div>
