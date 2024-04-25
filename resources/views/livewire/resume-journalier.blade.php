
{{-- @vite('resources/css/app.css') --}}
<div class="w-full">
    @php
        use App\Models\Depense;
        use App\Models\Loyer;
        use Carbon\Carbon;
    @endphp
    <link rel="stylesheet" href="{{asset('build/assets/app-2bf04d98.css') }}">
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="flex justify-between">
        <h1 class="text-2xl font-bold" style="padding-bottom: 25px;">Resumé journalier de {{ $mois }} {{$annee}}</h1>
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
                    @if($data != null || $data != null)
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
                    @endif    
                        
                </table>

            </div>
            <div>
                <table class="min-w-full divide-y  divide-gray-200 table-fixed dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr colspan="1" >
                            <p style="text-align:center">RESUME DE LA CAISSE DU {{NOW()->format('d/m/y')}}</p>
                        </tr>
                        <tr colspan="2" style="text-align: center">
                            <tr scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                <th scope="col" colspan="2" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    CAISSE
                                </th>
                                <th scope="col" colspan="2" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    BANQUE
                                </th>   
                            </tr>
                            
                            <tr>
                                <td scope="col" colspan="" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    DESIGNATION
                                </td>
                                <td scope="col" colspan="" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    MONTANT EN USD
                                </td>
                                
                                <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    DESIGNATION
                                </td>
                                
                                <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    MONTANT EN USD
                                </td>
                            </tr>
                        </tr>

                        <tbody>
                            <tr>
                                <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    prevision finale
                                </td>
                                <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    {{$prevFinale}}
                                </td>
                            </tr>
                            <tr>
                                <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    Entrées brut du jour
                                </td>
                                <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    {{$entreeBrut}}
                                </td>
                            </tr>
                            <tr>
                                <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    Prévision restante
                                </td>
                                <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    {{$prevFinale - $entreeBrut}}
                                </td>
                            </tr>
                            <tr>
                                <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    Solde du {{Carbon::yesterday()->format('d-m-Y')}}
                                </td>
                                <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    @php
                                        $total_depA=0;
                                        $total_loyA=0;
                                        $depensesA = Depense::whereDate('created_at', Carbon::yesterday())->get();
                                        foreach ($depensesA as $depenseA) {
                                            # code...
                                            $total_depA += $depenseA->qte*$depenseA->cu;
                                        }
                                        $loys = Loyer::whereDate('created_at', Carbon::yesterday())->get();
                                        foreach ($loys as $loy) {
                                            # code...
                                            $total_loyA += $loy->montant;
                                        }
                                    @endphp
                                    {{$total_loyA + $total_depA}}
                                </td>
                            </tr>
                            <tr>
                                <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    Entrées du jour
                                </td>
                                <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    {{$entreeBrut}}
                                </td>
                            </tr>
                            <tr>
                                <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    Dépenses du jour
                                </td>
                                <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    @php
                                        $total_dep=0;
                                        $depenses = Depense::whereDate('created_at', Carbon::today())->get();
                                        foreach ($depenses as $depense) {
                                            # code...
                                            $total_dep += $depense->qte*$depense->cu;
                                        }
                                    @endphp
                                    {{$total_dep}}$
                                </td>
                            </tr>
                            <tr>
                                <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    Solde du jour
                                </td>
                                <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    {{$entreeBrut-$total_dep}}$
                                </td>
                            </tr>
                            <tr>
                                <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    Solde final
                                </td>
                                <td scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    {{$total_loyA + $total_depA+($entreeBrut-$total_dep)}}$
                                </td>
                            </tr>

                        </tbody>

                    </thead>
                    
                </table>
            </div>
        </div>
      
    </div>

</div>

