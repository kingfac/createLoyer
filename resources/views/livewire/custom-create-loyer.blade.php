<div>
    <link rel="stylesheet" href="{{asset('build/assets/app-2bf04d98.css') }}">
    
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
            <p class="text-white flex-1 text-right">{{$locataire->noms}}</p>
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
        <p class="text-black ">Galérie: {{$locataire->occupation->galerie->nom}}-{{$locataire->occupation->galerie->num}}</p>
        <p class="text-black ">Type occupation: {{$locataire->occupation->typeOccu->nom}}</p>
        <p class="text-black ">Numéro occupation: {{$locataire->num_occupation}}</p>
        <p class="text-black ">Loyer: {{$locataire->occupation->montant}} $</p>
        <?php 
            use App\Models\Garantie;
            use App\Models\Loyer;
            use App\Models\Locataire;
            use App\Models\User;


            $garantie = Garantie::where(['locataire_id'=> $locataire->id, 'restitution' => false])->sum('montant');
            $paie_garantie = Loyer::where(['locataire_id' => $locataire->id, 'garantie' => true])->sum('montant');
        ?>
        <p class="text-black ">Garantie: {{$garantie - $paie_garantie ?? 'Aucune garantie'}}($)</p>
    </div>
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
        
    
        <h1 class=" ">Paiements effectués au mois de {{$mois}} / {{$annee}}</h1>
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
                    Intervenant
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
                $intervenant = User::find($ly->users_id)->first()->name;
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
                    {{$intervenant ?? '' }} 
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
    <div class=" flex justify-center" style="padding: 10px; ">
        {{-- <h1>Aucun paiement effectué au cours de ce mois-ci !</h1> --}}
        <img src="{{asset('img/No_data.svg')}}" alt="" srcset="" width="400">
    </div>
    @endif




    @if ($ap != null and $mp != null)
        @php

            $lo = Locataire::where('id',$locataire_id)->first();
            
        @endphp
    <div>

        <table class="fi-ta-table  table-auto divide-y divide-gray-600 text-start dark:divide-white/5 w-full " ">
            <h1 style=" color:#3b82f6 ; font-size:1.5em; margin-top: 15px ; font-weight : bold "> Affichage des dettes</h1>
                
                <thead class="bg-gray-100 dark:bg-gray-700" style="background-color: #ababab9f">
                    <tr class="text-lg font-bold">
                        <th scope="col" colspan="3" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Mois
                        </th>
    
                        <th scope="col" colspan="3" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Année
                        </th>
                        <th scope="col" colspan="3" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Loyer payé
                        </th>
    
                        <th scope="col" colspan="3" class="border py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                            Dette
                        </th>
                    </tr>
                </thead>
            
    
                <tbody class="divide-y divide-gray-200  dark:divide-white/5">
                
                    @php
                        $_id = 0;
                        $total = 0;
                        $i = 0;
                        // dd(($dettes_mois));
                    @endphp

                    
                    @for ($i=0; $i < count($dettes_mois); $i++) 
                    @php
                        // $total += $;
                    @endphp
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                        
                        
                        <td colspan="3" class="border w-32 py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                            {{$dettes_mois[$i]}}
                        </td>
                        <td colspan="3" class="border w-32 py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                            {{$dettes_annees[$i]}}
                        </td>
                        <td colspan="3" class="border w-32 py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                            {{$dettes_montant[$i]}} $
                        </td>
                        <td colspan="3" class="border w-32 py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">
                            @php
                                $total += $lo->occupation->montant - array_sum($dettes_montant);
                            @endphp
                            {{$lo->occupation->montant - array_sum($dettes_montant)}} $
                        </td>
                        
                    </tr>
                    @endfor
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                        <td colspan="3" class="border w-32 py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">Total</td>
                        <td colspan="3" class="border w-32 py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap"></td>
                        <td colspan="3" class="border w-32 py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap"></td>
                        <td colspan="3" class="border w-32 py-4 px-6 text-sm font-medium text-gray-900 whitespace-norwap">{{$total}} $</td>
                    </tr>
                </tbody>
    
            
            </table>
            
    
        @else
            <div>
                <span style=" width:100%; color:red; text-align:center; text-decoration:underline"> Veuillez spécifier le premier mois de paiement ou année de paiement du locataire pour afficher ses dettes.</span>
            </div>
        @endif
        

    </div>    
 </div>
