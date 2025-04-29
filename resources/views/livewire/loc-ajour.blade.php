
{{-- @vite('resources/css/app.css') --}}
<div class="w-full">

    <link rel="stylesheet" href="{{asset('build/assets/app-3e76f9e4.css') }}">


    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="flex justify-between">
        <h1 class="text-2xl font-bold" style="padding-bottom: 25px;">Locataire à jour du mois de : {{ $mois }}</h1>
        {{--  {{ $this->form }}
        {{ $this->table }} --}}

        <x-filament::icon-button
            icon="heroicon-o-table-cells"
            tag="a"
            label="Export to Excel"
            tooltip="Export to Excel"
            href="/storage/etat/ajour.xlsx"
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
        <div>{!! $htmlContent !!}</div>
    </div>
    {{-- <div class="overflow-x-auto shadow-md sm:rounded-lg">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden ">
                <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="p-4">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-blue-600 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 p-2"></div>
                                    <label for="checkbox-all" class="sr-only">checkbox</label>
                                </div>
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Locataire
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Galerie
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Type Occupation
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Loyer mensuel
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Loyer payé
                            </th>

                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">

                        @php
                            $_id = 0;
                            $ctrR = 0;
                            $somme =0;
                            $somme1 =0;
                        @endphp
                        @foreach ($data as $dt)
                        @if ($_id != $dt->id && $dt->occupation->montant == $dt->somme)
                        @php
                            $_id = $dt->id;
                            $ctrR +=1 ;
                        @endphp


                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                        <td class="p-4 w-4">
                            @if ($dt->somme == 0)
                            <div class="w-4 h-4 bg-blue-600 rounded border-gray-300  p-2"></div>
                            @else
                                @if ($dt->occupation->montant == $dt->somme)
                                <div class="w-4 h-4 bg-green-600 rounded border-gray-300  p-2"></div>
                                @else
                                <div class="w-4 h-4 bg-red-600 rounded border-gray-300  p-2"></div>
                                @endif
                            @endif
                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$dt->noms}}
                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$dt->occupation->galerie->nom}} - {{$dt->occupation->galerie->num}}
                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$dt->occupation->typeOccu->nom}}
                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            @php
                                $somme += $dt->occupation->montant;
                                $somme1 += $dt->somme;
                            @endphp
                            {{$dt->occupation->montant}}$
                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$dt->somme ?? 0}} $
                        </td>

                    </tr>

                        @endif

                        @endforeach
                        @if ($ctrR > 0)
                            <tr class="bg-gray-200 ">
                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Totaux
                                </td>
                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">

                                </td>
                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">

                                </td>
                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">

                                </td>
                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$somme}} $
                                </td>
                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$somme1}} $
                                </td>
                            </tr>

                        @endif
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
                        Loyer à payer
                    </td>

                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        Loyer payé
                    </td>
                </tr>
            </thead>


        <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">

            @php
                $_id = 0;
            @endphp
            @foreach ($data as $dt)
            @if ($_id != $dt->id && $dt->occupation->montant == $dt->somme)
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
                    {{$dt->occupation->montant}}
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->somme}}
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>


    </table> --}}

</div>
