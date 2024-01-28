<div>
    <div class="overflow-x-auto shadow-md sm:rounded-lg">
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
                                Numero
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Galerie
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Loyers total
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Dettes antérieures
                            </th>

                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @php
                            $sommeGarentie = 0;
                            $sommeLoyerApay = 0;
                            $sommeLoyerPay = 0;
                            $voir = 0;
                        @endphp
                        @foreach ($data as $dt)
                        @php
                        /* ...........nombre d'occupations dans une galerie..................... */
                        
                        /* ...........nombre de locataires dans une occupation.................. */

                            for($i = 0; $i < $dt->occupations->count(); $i++){
                                $sommeLoyerApay +=$dt->occupations[$i]->locataires->count() * $dt->occupations[$i]->montant;
                                foreach ($dt->occupations[$i]->locataires as $loc) {
                                    if($loc->actif){
                                        
                                    }
                                }
                                /* for ($j=0; $j < $dt->occupations[$i]->locataires->count() ; $j++) { 
                                    if ($dt->occupations[$i]->locataires[$j]->actif == true) {
                                        for($k = 0; $k < $dt->occupations[$i]->locataires[$j]->loyers->count(); $k++){
                                             dd($dt->occupations[$i]->locataires[$j]->loyers[$k]->montant;
                                        } 
                                    }
                                } */

                            }
                        @endphp

                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                            <td class="p-4 w-4">
                               {{-- @if ($dt->somme == 0)
                                <div class="w-4 h-4 bg-blue-600 rounded border-gray-300  p-2"></div>
                                @else
                                    @if ($dt->occupation->montant == $dt->somme)
                                    <div class="w-4 h-4 bg-green-600 rounded border-gray-300  p-2"></div>
                                    @else
                                    <div class="w-4 h-4 bg-red-600 rounded border-gray-300  p-2"></div>
                                    @endif
                                @endif  --}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->id}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->nom}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                 {{$sommeLoyerApay}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                
                            </td>
                        </tr>
                        @php
                            $sommeLoyerApay = 0;
                        @endphp
                        @endforeach
                       
                    </tbody>
                </table>
            </div>
        </div>

</div>
