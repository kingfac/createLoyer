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
    <h2>{{$label}}</h2>
</div>

<div class="text-center b-2 bg-gray-500 mb-2">{{$label}}</div>

<table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'">
        
    <thead class="bg-gray-50 dark:bg-white/5">
        <tr class="text-lg font-bold">
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                Nom
            </td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                Galerie
            </td>

            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                Occupation
            </td>

            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                Loyer
            </td>
            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                Date
            </td>
        </tr>
    </thead>


<tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
 
    @foreach ($data as $dt) 
    

    <tr>
        
        <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
            {{$dt->locataire->noms}}
        </td>
        <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
            {{$dt->locataire->occupation->galerie->nom}}
        </td>
        <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
            {{$dt->locataire->occupation->typeOccu->nom}}
        </td>
        <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
            {{$dt->montant}} $
        </td>
        <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
            {{$dt->created_at}} 
        </td>
        

    </tr>
    
    @endforeach
</tbody>


</table>