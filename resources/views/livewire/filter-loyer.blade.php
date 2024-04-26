{{-- @vite('resources/css/app.css') --}}
<div class="w-full">
    @filamentStyles
    <link rel="stylesheet" href="{{asset('build/assets/app-2bf04d98.css') }}">
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="flex  justify-between">
        <h1 class="text-2xl font-bold" style="padding-bottom: 25px;">Evolution des paiements du mois de : {{ $mois }}</h1>
        <x-filament::icon-button
            icon="heroicon-o-printer"
            tag="a"
            label="imprimer"
            tooltip="Imprimer"
            href="/storage/pdf/doc.pdf"
            target="_blank"
            
        />
    </div>
    
    
    {{-- {{ $this->table }} --}}

    <div class="overflow-x-auto shadow-md sm:rounded-lg bg-red-500">
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
                                Galerie / Type 
                            </th>
                            {{-- <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Garantie
                            </th> --}}
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
                            use App\Models\Loyer;

                            $_id = 0;
                            $ctrR = 0;
                            $total_paye=0;
                            $total_reste=0;
                            $loyer_mens = 0;

                        @endphp
                        @foreach ($data as $dt) 
                     
                        @if ($_id != $dt->id)
                        @php
                            $_id = $dt->id;
                            $ctrR +=1 ;
                            $total_paye += $dt->somme;
                            $total_reste += ($dt->occupation->montant - $dt->somme);
                        @endphp
                        
                          
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                            <td class="p-4 w-4">
                                @if ($dt->somme == 0)
                                <div class="w-4 h-4 bg-red-600 rounded border-gray-300  p-2"></div>
                                @else
                                    @if ($dt->occupation->montant == $dt->somme)
                                    <div class="w-4 h-4 bg-green-600 rounded border-gray-300  p-2"></div>
                                    @else
                                    <div class="w-4 h-4 bg-blue-600 rounded border-gray-300  p-2"></div>
                                    @endif
                                @endif
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->noms}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->occupation->galerie->nom}}-{{$dt->occupation->galerie->num}} / {{$dt->occupation->typeOccu->nom}}
                            </td>
                            {{-- <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                @php
                                    $paie_garantie = Loyer::where('locataire_id', $dt->id)->where('garantie',true)->sum('montant');
                                @endphp
                                {{$dt->garantie  - $paie_garantie}}
                            </td> --}}
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->occupation->montant}}$
                                @php
                                    $loyer_mens += $dt->occupation->montant;
                                @endphp
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
                                {{$loyer_mens}} $
                            </td>         
                            <td class="py-4 px-6 text-sm font-medium bg-gray-200 text-gray-900 whitespace-nowrap dark:text-white">
                                {{$total_paye}} $
                            </td>     
                            <td class="py-4 px-6 text-sm font-medium bg-gray-200 text-gray-900 whitespace-nowrap dark:text-white">
                                {{$total_reste}} $
                            </td>                              
                        </tr>
                       
                    </tbody>
                </table>
            </div>
        </div>
        @if ($ctrR == 0)
        <div class="flex justify-center items-center text-2xl text-red-400 p-10">
            <h1>Pas de données disponibles...</h1>
        </div>
        @endif
    </div>

  {{--   <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'">
        
        <thead class="bg-gray-50 dark:bg-white/5">
            <tr class="text-lg font-bold">
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    E
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
        @if ($_id != $dt->id )
        @php
            $_id = $dt->id;
            $ctrR +=1;
        @endphp
        <tr>
            @if ($dt->somme == 0)
                
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: red;">■</td>
            @else
               @if ($dt->occupation->montant == $dt->somme)
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">■</td>
               @else
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: blue;">■</td>       
               @endif 
            @endif
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                {{$dt->noms}}
            </td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                {{$dt->garantie}}$
            </td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                {{$dt->occupation->montant}}$
            </td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                {{$dt->somme ?? 0}} $
            </td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                {{$dt->occupation->montant - $dt->somme}} $
            </td>

        </tr>
        @endif
        @endforeach
    </tbody>

   
</table> --}}
</div>