
{{-- @vite('resources/css/app.css') --}}
<div class="w-full">
    <link rel="stylesheet" href="{{asset('build/assets/app-247549ac.css')}}">
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="flex justify-between">
        <h1 class="text-2xl font-bold" style="padding-bottom: 25px;">Locataire à jour du mois de : {{ $mois }}</h1>
        {{--  {{ $this->form }}
        {{ $this->table }} --}}
        <x-filament::icon-button
            icon="heroicon-o-printer"
            tag="a"
            label="imprimer"
            tooltip="Imprimer"
            href="/storage/pdf/doc.pdf"
            target="_blank"
                
        />
    </div>
    <div class="overflow-x-auto shadow-md sm:rounded-lg">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden ">
                <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <tr scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                <th scope="col" colspan="3" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    CAISSE
                                </th>
                                <th scope="col" colspan="2" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    BANQUE
                                </th>   
                            </tr>

                        </tr>
                        <tr>
                            <td scope="col" colspan="" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                DEPENSES Ste
                            </td>
                            <td scope="col" colspan="" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                DEPENSES HORS Ste
                            </td>
                            
                            <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                LIBELLE
                            </td>
                            <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                MONTANT EN USD
                            </td>
                            
                            <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                MOTIFS
                            </td>
                        </tr>

                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">

                        @php
                            $_id = 0;
                            $total1=0;
                            $total2=0;
                            $total3=0;
                            $ctrR = 0;
                            foreach ($data as $dt) {
                                $total1 += $dt->cu*$dt->qte;
                            }

                            foreach ($data1 as $dt) {
                                $total2 += $dt->cu*$dt->qte;
                            }

                            foreach ($data2 as $dt) {
                                $total3 += $dt->cu*$dt->qte;
                            }

                            $total = $total1 + $total2 + $total3;
                        @endphp
                        
                        @foreach ($data as $dt) 
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                            
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->cu*$dt->qte}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{-- {{$dt->besoin}} --}}

                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->besoin}}

                            </td>
                        </tr>
                        <tr>

                        </tr>

                        @endforeach

                        @foreach ($data1 as $dt) 
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                            
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->cu*$dt->qte}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{-- {{$dt->besoin}} --}}

                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->besoin}}

                            </td>
                        </tr>
                        @endforeach


                        @foreach ($data2 as $dt) 
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                            
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{-- {{$dt->cu*$dt->qte}} --}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->cu*$dt->qte}}

                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->besoin}}

                            </td>
                        </tr>
                   

                        @endforeach
                        
                        <tr class="text-xl" style=" font:bold; size:1.6em;">
                            <td colspan="4" class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">Total</td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$total}} $</td>
                        </tr>
                        
                     
                       
                    </tbody>
                </table>
            </div>
        </div>
      
    </div>

</div>

