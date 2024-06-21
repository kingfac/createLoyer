<link rel="stylesheet" href="{{public_path('css.css')}}"> 
@php
    use Carbon\Carbon;
    $lelo = new DateTime('now');
    $lelo = $lelo->format('d-m-Y').' à '.$lelo->format('H:i');
@endphp
<div class="" >
    <div class=" text-center w-full">
        
        <table style=" width:100%; font-size: 1em; font-weight: bold; color:rgb(46, 131, 211)">
            <tr  style="">
                <td  style="">
        
                    <div class="text-start" style="">
                        <h2>MILLE ET UNE MERVEILLE</h2>
                        <h3>RCCM/15-B-9122</h3>
                        <h3>N.R.C. 53666 - Id. Nat. : 01-910-N 40270K</h3>
                        <h3>Av. Tshuapa N°90 C./Kinshasa</h3>
                        <h3 style=" border-bottom:solid 1px; borcer-bottom-width:100px;">Tel. : 0850758588 - 0816567028</h3>
                    </div>
                </td>
                <td style="text-align:right;" colspan="3">
                    @php
                        $lelo = Carbon::today()->format('d-m-Y');
                    @endphp
                    <h4>Kin, le {{$lelo}}</h4>
                </td>
            </tr>
        </table>
    </div>

    <div class="text-center text-2xl b-2 bg-gray-500 mb-2" style="font-weight:bold;padding:15px">{{$label}}</div>

    <hr>
    {{-- Nothing in the world is as soft and yielding as water. --}}
   
    
        
    <table class="" style=" width:100%;">
        
        <thead class="bg-gray-100 dark:bg-gray-700"  style="background-color: rgb(218, 218, 218)">
            <tr class="border-b">
                <td scope="col" class=" pxx-3 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    N°
                </td>
                <td scope="col" class=" pxx-3 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Locataire
                </td>

                <td scope="col" class=" pxx-3 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Galerie
                </td>

                <td scope="col" class=" pxx-3 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Type occup
                </td>
                <td  scope="col" class=" pxx-3 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Num occup
                </td>
                <td scope="col" class=" pxx-3 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Total
                </td>
                <td scope="col"  class=" pxx-3 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Date de sortie
                </td>
               
            </tr>
        </thead>
    

        <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
        
            @php
                $num=1;
            @endphp
            @foreach ($garanties as $record)
                <tr class="hover:bg-white/5 dark:hover:bg-white/5">
                    <td  class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        {{$num}}
                        @php
                            $num+=1;
                        @endphp
                    </td>
                    <td  class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        {{$record->locataire->noms}}
                    </td>
                    <td  class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        {{$record->locataire->occupation->galerie->nom. '-'.$record->locataire->occupation->galerie->num}}
                    </td>
                    <td  class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        {{$record->locataire->occupation->typeOccu->nom}}
                    </td>
                    <td  class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        {{$record->locataire->num_occupation}}
                    </td>

                    <td  class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3"></td>
                        {{$record->montant}} $
                    </td>
                    <td  class="fi-ta-cell px-2 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        {{$record->created_at}}
                    </td>
                
                    
                </tr>
                
            @endforeach
            {{-- <tr class="text-lg font-bold bg-gray-50">
                <td class="">Total</td>
                <td>{{$total}} $</td>
            </tr> --}}
        </tbody>
        <tfoot style="background-color: rgb(218, 218, 218)">
            <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                Total
            </td>
            <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
            </td>
            <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
            </td>
            <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
            </td>
            <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
            </td>
            <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                {{$garanties->sum('montant')}} $
            </td>
            <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
            </td>
        </tfoot>
    
    </table>
{{--    
    @php
        $lelo = new DateTime('now');
        $lelo = $lelo->format('d-m-Y');
    @endphp --}}

    {{-- <div class="w-full" style=" text-align:right; margin-top:30px;">
        <p>Aujourd'hui le, {{$lelo}}</p>
    </div> --}}
</div>

