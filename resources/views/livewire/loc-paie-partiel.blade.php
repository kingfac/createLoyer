<div class="w-full">
    @filamentStyles
    {{-- @vite('resources/css/app.css') --}}
    <link rel="stylesheet" href="{{asset('build/assets/app-3e76f9e4.css') }}">
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}

    <div class="flex justify-between">
        <h1 class="text-2xl font-bold" style="padding-bottom: 25px;">Locataire avec paiement partiel du mois de : {{ $mois }}</h1>
        {{--  {{ $this->form }}
        {{ $this->table }} --}}
        <x-filament::icon-button
            icon="heroicon-o-table-cells"
            tag="a"
            label="Export to Excel"
            tooltip="Export to Excel"
            href="/storage/etat/partial.xlsx"
            target="_blank"

        />
        {{-- <button wire:click="exportExcel" class="px-4 py-2 bg-green-500 rounded">
            Export to Excel
        </button> --}}
    </div>
    <style>
        table {
            width: 100%;
        }
        tr{
            border-bottom: solid 1px;
        }
    </style>
    <div class="">
        {{-- {{ $this->table}} --}}
        <div>{!! $htmlContent !!}</div>
    </div>
   {{--  {{ $this->form }}
    {{ $this->table }} --}}
    {{-- <div class="overflow-x-auto shadow-md sm:rounded-lg ">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden ">
                <table class=" divide-y divide-gray-200 table-fixed dark:divide-gray-700 w-full">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>

                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Locataire
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Galerie / Type Occupation
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Loyer mensuel
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Loyer payé
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Reste
                            </th>

                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @php
                            $_id = 0;
                            $ctrR = 0;
                            $total_paye=0;
                            $total_reste=0;

                        @endphp
                        @foreach ($data as $dt)
                        @if ($_id != $dt->id && $dt->somme < $dt->occupation->montant && $dt->somme > 0)
                        @php
                            $_id = $dt->id;
                            $ctrR += 1;
                            $total_paye += $dt->somme;
                            $total_reste = $total_reste + ($dt->occupation->montant - $dt->somme)
                        @endphp


                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">

                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->noms}}
                            </td>

                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->occupation->galerie->nom}}-{{$dt->occupation->galerie->num }}/ {{$dt->occupation->typeOccu->nom}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->occupation->montant}}$
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->somme ?? 0}} $
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->occupation->montant - $dt->somme}} $
                            </td>

                        </tr>

                        @endif
                        @endforeach
                        <tr>
                            <td class="py-4 px-6 text-sm font-medium bg-gray-200 text-gray-900 whitespace-nowrap dark:text-white">
                                Totaux
                            </td>
                            <td class="py-4 px-6 text-sm font-medium bg-gray-200 text-gray-900 whitespace-nowrap dark:text-white">
                            </td>
                            <td class="py-4 px-6 text-sm font-medium bg-gray-200 text-gray-900 whitespace-nowrap dark:text-white">
                            </td>
                            <td class="py-4 px-6 text-sm font-medium bg-gray-200 text-gray-900 whitespace-nowrap dark:text-white">
                                {{$total_paye}}$
                            </td>
                            <td class="py-4 px-6 text-sm font-medium bg-gray-200 text-gray-900 whitespace-nowrap dark:text-white">
                                {{$total_reste}}$
                            </td>
                        </tr>

                    </tbody>
                </table>

            </div>
        </div>
        <div class="pagination py-5 flex justify-between">
            <p>Par pages : {{$perPage}}</p>
            <div>
                <label for="perPage">Items per page:</label>
                <select wire:model.change="perPage" id="perPage" class="px-5">
                    @foreach ($perPageOptions as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                @if ($total_page > 1)
                    @if ($start_page > 1)
                        <button wire:click="gotoPage({{ $start_page - 1 }})" class="border p-2 cursor-pointer"><</button>
                    @endif

                    @for ($i = 1; $i <= $total_page; $i++)
                        <button wire:click="gotoPage({{ $i }})"
                            @if ($i == $start_page) style="font-weight: bold;" @endif  class="p-2 border cursor-pointer">
                            {{ $i }}
                        </button>
                    @endfor

                    @if ($start_page < $total_page)
                        <button wire:click="gotoPage({{ $start_page + 1 }})" class="p-2 border cursor-pointer">></button>
                    @endif
                @endif
            </div>
        </div>
        @if ($ctrR == 0)
        <div class="flex justify-center items-center text-2xl text-red-400 p-10">
            <h1>Pas de données disponibles...</h1>
        </div>
        @endif
    </div> --}}


    {{-- <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'">

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


    </table> --}}

</div>
