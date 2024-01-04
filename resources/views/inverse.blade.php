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

<table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'">
        
    <thead class="bg-gray-50 dark:bg-white/5">
        <tr class="text-lg font-bold">
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                E
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
    @if ($_id != $dt->id && $dt->somme == 0)
    @php
        $_id = $dt->id;
    @endphp
    <tr>
        @if ($dt->somme == 0)
            
        <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: red;">#</td>
        @else
           @if ($dt->occupation->montant == $dt->somme)
        <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">#</td>
           @else
        <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: blue;">#</td>       
           @endif 
        @endif
        <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
            {{$dt->noms}}
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


</table>