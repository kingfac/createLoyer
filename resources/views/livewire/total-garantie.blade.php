
{{-- @vite('resources/css/app.css') --}}
<div class="w-full">
    <link rel="stylesheet" href="{{asset('build/assets/app-2bf04d98.css') }}">
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="flex justify-between">
        <h1 class="text-2xl font-bold" style="padding-bottom: 25px;">Total garanties des locataires</h1>
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
                            <td>N°</td>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Noms des locataires 
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Galeries
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Occupations
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                Montant Garanties
                            </th>                            
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">

                        @php
                            $_id = 0;
                            $ctrR = 0;
                            $total = 0;
                        @endphp
                        
                        @foreach ($data as $dt) 
                        
                        @php
                            $ctrR += 1;
                            //$total += $dt->montant;
                        @endphp
                        
                        @php
                        $totalg = 0
                        @endphp
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                            <td class="p-4 w-4">
                                {{$loop->index + 1}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->noms}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->occupation->galerie->nom}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$dt->occupation->typeOccu->nom}}
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{-- {{$dt->occupation->montant}}$ --}}
                                
                                @foreach ($dt->garanties as $gar)
                                @if ($gar->restitution == false)
                                    @php
                                        $totalg += $gar->montant;
                                    @endphp
                                @endif
                                @endforeach
                                {{$totalg}} $
                            </td>
                           
                        </tr>
                        
                        
                        @php
                            $total += $totalg;
                        @endphp
                        @endforeach
                       <tr class="text-xl bg-gray-200" style=" font:bold; size:1.6em;">
                        <td colspan="4" class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">Total</td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$total}} $</td>
                       </tr>
                    </tbody>
                </table>
                @php
                    $lelo = new DateTime('now');
                    $lelo = $lelo->format('d-m-Y');
                @endphp

                
            </div>
        </div>
       {{--  <div class="text-orange-400">kfkf</div>
        <div class="text-yellow-400">kfkf</div>
        <div class="text-blue-400">kfkf</div>
        <div class="text-green-400">kfkf</div>
        <div class="text-slate-400">kfkf</div> --}}
        @if ($ctrR == 0)
        <div class="flex justify-center items-center text-2xl text-red-400 p-10">
            <h1>Pas de données disponibles...</h1>
        </div>
        @endif
    </div>

    

</div>
