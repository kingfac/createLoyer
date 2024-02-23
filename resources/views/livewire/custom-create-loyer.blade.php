<div>
    <link rel="stylesheet" href="{{asset('build/assets/app-247549ac.css')}}">
    
    {{-- Nothing in the world is as soft and yielding as water. --}}
    {{-- <style>
        h1{
            font-size: 1.2em;
            font-weight: bold;
        }

        h2{
            
        }
    </style> --}}
    <div class="flex justify-between " >
        {{-- {{$dettes_mois($locataire->id)}} --}}
        <div class=" w-full flex justify-between  bg-blue-600 p-6 ">
            <h1 class=" text-xl text-white uppercase">Etat personnel du locataire</h1>
            <p class="text-white opacity-5 flex-1 text-right">{{$locataire->noms}}</p>
            <div align='right' style=" padding-left: 10px;">
                <x-filament::icon-button
                icon="heroicon-m-x-mark"
                tag="a"
                label="Fermer"
                wire:click="fermer"
                color="white"
                />
            </div>
        </div>
        
       
    </div>
    <div class=" w-full p-3 bg-gray-100 flex justify-between">
        <p class="text-black opacity-5">Galérie: {{$locataire->occupation->galerie->nom}}</p>
        <p class="text-black opacity-5">Type occupation: {{$locataire->occupation->typeOccu->nom}}</p>
        <p class="text-black opacity-5">Numéro occupation: {{$locataire->num_occupation}}</p>
        <p class="text-black opacity-5">Loyer: {{$locataire->occupation->montant}} $</p>
        <?php 
            use App\Models\Garantie;
            use App\Models\Loyer;

            $garantie = Garantie::where(['locataire_id'=> $locataire->id, 'restitution' => false])->sum('montant');
            $paie_garantie = Loyer::where(['locataire_id' => $locataire->id, 'garantie' => true])->sum('montant');
        ?>
        <p class="text-black opacity-5">Garantie: {{$garantie - $paie_garantie ?? 'Aucune garantie'}}($)</p>
    </div>

    {{-- <div class=" flex justify-between gap-8 ">
        <div class=" bg-blue-600 shadow-xl  text-center  text-white  m-10 rounded-md px-5 py-3">
            Locataire
            <h2 class="  text-white px-5 rounded-md ">{{$locataire->noms}}</h2>
        </div>
        <div class=" bg-blue-600 shadow-xl  text-center w-80  text-white text-xl  rounded-md  m-10 px-5 py-3">
            Galerie
            <h2 class="  text-white rounded-md">{{$locataire->occupation->galerie->nom}}</h2>
        </div>
        <div class=" bg-blue-600 shadow-xl text-center w-80  text-white text-xl rounded-md  m-10 px-5 py-3">
            Type occupation
            <h2 class="  text-white rounded-md">{{$locataire->occupation->typeOccu->nom}}</h2>
        </div>
        <div class=" bg-blue-600 shadow-xl text-center w-80  text-white text-xl rounded-md  m-10 px-5 py-3">
            Numéro occupation
            <h2 class="  text-white rounded-md">{{$locataire->num_occupation}}</h2>
        </div>
        <div class=" bg-blue-600 shadow-xl shadow-gray-400  w-80 text-center rounded-md  text-white text-xl m-10 px-5 py-3">
            Loyer à payer
            <h2 class="  text-white rounded-md">{{$locataire->occupation->montant}} $</h2>
        </div>
        <div class=" bg-blue-600 shadow-xl shadow-gray-400  w-80 text-center rounded-md  text-white text-xl m-10 px-5 py-3">
            Garantie
            <?php 
            use App\Models\Garantie;
            use App\Models\Loyer;

            $garantie = Garantie::where(['locataire_id'=> $locataire->id, 'restitution' => false])->sum('montant');
            $paie_garantie = Loyer::where(['locataire_id' => $locataire->id, 'garantie' => true])->sum('montant');
            ?>
            <h2 class="  text-white rounded-md">{{$garantie - $paie_garantie ?? 'Aucune garantie'}}($)</h2>
        </div>
    </div> --}}

    {{-- <div class="flex justify-between py-2">
        <div>
            <h1>NOM DU LOCATAIRE</h1>
            <h2>{{$locataire->noms}}</h2>
        </div>
        <div>
            <h1>Galerie</h1>
            <h2>{{$locataire->occupation->galerie->nom}}</h2>
        </div>
        <div>
            <h1>Type occupation</h1>
            <h2>{{$locataire->occupation->typeOccu->nom}}</h2>
        </div>
        <div>
            <h1>Loyer à payer</h1>
            <h2>{{$locataire->occupation->montant}} $</h2>
        </div>
        <div>
            <h1>GARANTIE</h1>
            <?php 
                use App\Models\Garantie;
                use App\Models\Loyer;

                $garantie = Garantie::where(['locataire_id'=> $locataire->id, 'restitution' => false])->sum('montant');
                $paie_garantie = Loyer::where(['locataire_id' => $locataire->id, 'garantie' => true])->sum('montant');
            ?>
            <h2>{{$garantie - $paie_garantie ?? 'Aucune garantie'}}($)</h2>
        </div>
        {<div>
            <h1>DETTES</h1>
            <?php 
                $result = $this->calculDettes($locataire->id)
            ?>
            <h2>{{$result ?? 'Aucune garantie'}}($)</h2>
        </div> 
    </div> --}}
    <div class="py-8 flex justify-between items-center flex-col">
        <div class=" ">
            @if ($locataire->actif)
            <div class="">
                {{$this->form}}
            </div>
            <div class=" flex gap-5 py-4  ">
                <button wire:click="create"  class="bg-blue-500   text-white px-5 py-2 text-center rounded-md ">
                    Créer
                </button>
                <a href="/storage/pdf/doc.pdf" class="bg-blue-500  text-white px-5 py-2 rounded-md" target="_blank">
                    {{-- <x-heroicon-s-printer /> --}}
                    Imprimer
                </a>
                    <div class="bg-red-500   text-white px-5 py-2 text-center rounded-md ">
                        Dettes: 
                    </div>
                    <div class=" flex gap-4 px-10 justify-between  text-right">
                        @foreach ($dettes_mois as $dette)
                                <span class=" bg-gray-300 text-black p-3 rouded-xl">{{$dette}}</span>
                        @endforeach
                    </div>

            </div>
            @else
                <div  class="flex justify-center items-center">
                    <p>Le locataire a déjà liberé l'occupation {{$locataire->num_occupation}}</p>
                </div>
                <button type="" class="bg-blue-500 text-white p-4 rounded-md" target="_blank">
                    <x-heroicon-s-printer />
                    Imprimer
                </button>
            @endif
            

            
        </div>
        
    
        {{-- <h1 class=" ">Paiements effectués au mois de {{$mois}} / {{$annee}}</h1> --}}
    </div>

    @if (count($data) > 0)
        
    <table class="fi-ta-table w-full table-auto divide-y divide-gray-600 text-start dark:divide-white/5'">
        
        <thead class="bg-gray-100 dark:bg-gray-700" style="background-color: #ababab9f">
            <tr class="text-lg font-bold">
                <th scope="col" colspan="3" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Date de paiment
                </th>

                <th scope="col" colspan="3" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Loyer payé
                </th>
                <th scope="col" colspan="3" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Type payement
                </th>

                <th scope="col" colspan="3" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Reste
                </th>
                <th scope="col" colspan="3" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    Observation
                </th>
               
            </tr>
        </thead>
    

        <tbody class="divide-y divide-gray-200  dark:divide-white/5">
        
            @php
                $_id = 0;
                $total = 0;
            @endphp
            @foreach ($data as $ly) 
            @php
                $total += $ly->montant;
            @endphp
            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                
                
                <td colspan="3" class="border w-32 py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                    {{$ly->date_loyer}}
                </td>
                <td colspan="3" class="border w-32 py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                    {{$ly->montant ?? 0}} $
                </td>
                <td colspan="3" class="border w-32 py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                    {{$ly->garantie ? 'Avec garantie' : 'Sans garantie'}} 
                </td>
                <td colspan="3" class="border w-32 py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                    {{$ly->occupation->montant - $total}} $
                </td>

                <td colspan="3" class="border w-32 py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                    {{$ly->observation ?? 'Aucune observation'}} 
                </td>

                
            </tr>
            @endforeach
            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                <td colspan="3" class="border w-32 py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">Total</td>
                <td colspan="3" class="border w-32 py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">{{$total}} $</td>
            </tr>
        </tbody>

    
    </table>
    @else
    <div class=" flex justify-center" style="padding: 10px;">
        {{-- <h1>Aucun paiement effectué au cours de ce mois-ci !</h1> --}}
        <img src="{{asset('img/No_data.svg')}}" alt="" srcset="" width="400">
    </div>
    @endif
    
   



 </div>
