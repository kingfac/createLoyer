<div class="w-full">
    @filamentStyles
    {{-- @vite('resources/css/app.css') --}}
    <link rel="stylesheet" href="{{asset('build/assets/app-2bf04d98.css') }}">
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}

   {{--  {{ $this->form }}
    {{ $this->table }} --}}
    <div class="flex  justify-between items-center">
        <h1 class="text-2xl font-bold" style="padding-bottom: 25px;">Locataires avec soldes impayés : {{ $mois }}</h1>
        <x-filament::icon-button
            icon="heroicon-o-printer"
            tag="a"
            label="imprimer"
            tooltip="Imprimer"
            href="/storage/pdf/doc.pdf"
            target="_blank"

        />
        <button wire:click="exportExcel" class="px-4 py-2 bg-green-500 rounded">
            Exporter en Excel
        </button>
    </div>
    <style>
        table {
            width: 100%;
        }
    </style>
    <div class="">
        {{-- {{ $this->table}} --}}
        <div>{!! $htmlContent !!}</div>
    </div>

    <div class="overflow-x-auto shadow-md sm:rounded-lg bg-red-500">
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


                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">

                        @php
                            $_id = 0;
                            $ctrR = 0;
                            $lM = 0;
                            $lP = 0;
                        @endphp
                        @foreach ($data as $dt)
                        @if ($_id != $dt->id && $dt->somme == null)
                        @php
                            $_id = $dt->id;
                            $ctrR +=1;
                            $lM +=  $dt->occupation->montant;
                            $lP += $dt->somme ?? 0;
                        @endphp


                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">

                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->noms}}
                            </td>

                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$dt->occupation->galerie->nom}} - {{$dt->occupation->galerie->num}} / {{$dt->occupation->typeOccu->nom}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->occupation->montant}}$
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->somme ?? 0}} $
                            </td>
                        </tr>
                        @endif
                        @endforeach
                        <tr>

                    </tbody>
                    <tfoot class=" bg-gray-300">
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Totaux
                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$lM}}$
                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$lP}}$
                        </td>

                    </tr>
                    </tfoot>

                </table>
            </div>
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

</div>
