<style>
    .text-center {
        text-align: center;
    }
    .b-2{
        border:solid 2px
    }
    .b-1{
        border:solid 1px #ababab;
    }
    .bg-gray-500{
        background:#ababab;
    }
    .mb-2{
        margin-bottom: 20px;
    }
    .pb-2{
        padding-bottom:20px;
    }
    .pt-2{
        padding-top:20px;
    }
    .text-bold{
        font-weight: bold;
    }
    .py-2{
        padding-bottom:20px;
        padding-top:20px;
    }
    .w-full{
        width: 100%;
    }
    .text-r{
        text-align: right;
    }
    #t2 td{
        border:solid 1px #ababab;
    }
    #t2 tr{
        border:solid 1px #ababab;
    }

</style>
<div class=" text-center">
    <img src="https://static.vecteezy.com/ti/vecteur-libre/t2/620985-vecteur-de-modele-de-logos-maison-et-maison-gratuit-vectoriel.jpg">
    <h2>MILLE ET UNE MERVEILLE</h2>
</div>

<div class="text-center b-2 bg-gray-500 mb-2">{{$label}}</div>


    {{-- Nothing in the world is as soft and yielding as water. --}}
    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'">
        <tr>
            <td>
                <h3>NOM DU LOCATAIRE</h3>
            </td>
            <td>
                <h2>{{$locataire->noms}}</h2>
            </td>
        </tr>
        <tr>
            <td>
                <h3>Galerie</h3>
            </td>
            <td>
                <h3>Type occupation</h3>
            </td>
            <td>
                <h3>Loyer à payer</h3>
            </td>
        </td>
        <tr>
            <td>
                <h2>{{$locataire->occupation->galerie->nom}}</h2>
            </td>
            <td>
                <h2>{{$locataire->occupation->typeOccu->nom}}</h2>
            </td>
            <td>
                <h2>{{$locataire->occupation->montant}} $</h2>
            </td>
        </tr>

    </table>
    <hr>
    <div class="py-2 flex justify-between items-center flex-col gap-2">
       
        <h2>Paiements effectués au mois de {{$mois}} / {{$annee}}</h2>
    </div>

    <br>
    
        
    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5" border="0.5">
        
        <thead class="bg-gray-50 dark:bg-white/5">
            <tr class="text-lg font-bold">
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Date
                </td>

                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Loyer payé
                </td>

                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Reste
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Observation
                </td>
               
            </tr>
        </thead>
    

        <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
        
            @php
                $total = 0;
            @endphp
            @foreach ($data as $ly) 
            @php
                $total += $ly->montant;
            @endphp
            <tr class="hover:bg-white/5 dark:hover:bg-white/5">
                
                
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$ly->date_loyer}}
                </td>
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$ly->montant ?? 0}} $
                </td>
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$ly->occupation->montant - $total}} $
                </td>

                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$ly->observation ?? 'Aucune observation'}} 
                </td>

               
                
            </tr>
            @endforeach
            <tr class="text-lg font-bold bg-gray-50">
                <td class="">Total</td>
                <td>{{$total}} $</td>
            </tr>
        </tbody>

    
    </table>
   
    

