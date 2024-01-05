@filamentStyles
{{-- @vite('resources/css/app.css') --}}
<link rel="stylesheet" href="{{asset('build/assets/app-514a0b6d.css')}}">
<div class="w-full">
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    
   {{--  {{ $this->form }}
    {{ $this->table }} --}}
    <div class="flex  justify-between">
        <h1 class="text-2xl font-bold" style="padding-bottom: 25px;">Locataire avec solde impayé : {{ $mois }}</h1>
        <x-filament::icon-button
            icon="heroicon-o-printer"
            tag="a"
            label="imprimer"
            tooltip="Imprimer"
            href="/pdf/doc.pdf"
            target="_blank"
            
        />
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
                        @endphp
                        @foreach ($data as $dt) 
                        @if ($_id != $dt->id && $dt->somme == null)
                        @php
                            $_id = $dt->id;
                            $ctrR +=1;
                        @endphp
                        
                          
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                            
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->noms}}
                            </td>
                            
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->occupation->galerie->nom}} / {{$dt->occupation->typeOccu->nom}}
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
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if ($ctrR == 0)
    <div class="flex justify-center items-center text-2xl text-red-400 p-10">
        <h1>Pas de données disponibles...</h1>
    </div>
    @endif

</div>
