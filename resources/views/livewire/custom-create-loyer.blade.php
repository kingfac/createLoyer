<div>
    <link rel="stylesheet" href="{{asset('build/assets/app-247549ac.css')}}">
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <style>
        h1{
            font-size: 1.2em;
            font-weight: bold;
        }

        h2{
            
        }
    </style>
    <div class="flex justify-between" >
        <h1>Etat personnel du locataire</h1>
        <x-filament::icon-button
            icon="heroicon-m-x-mark"
            tag="a"
            label="Fermer"
            wire:click="fermer"
        />
    </div>
    <hr>
    <div class="flex justify-between py-2">
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
    </div>
    <hr>
    <div class="py-2 flex justify-between items-center flex-col gap-2">
        <div class="flex gap-2">
            @if ($locataire->actif)
            {{$this->form}}
            <button wire:click="create" class="bg-blue-500 text-white p-4 rounded-md">
                Créer
            </button>
            <a href="/storage/pdf/doc.pdf" class="bg-blue-500 text-white p-4 rounded-md" target="_blank">
                <x-heroicon-s-printer />
                imprimer
            </a>
            @else
                <div  class="flex justify-center items-center">
                    <p>Le locataire a déjà liberé l'occupation {{$locataire->num_occupation}}</p>
                    <button type="" class="bg-blue-500 text-white p-4 rounded-md" target="_blank">
                        <x-heroicon-s-printer />
                        imprimer
                    </button>
                </div>
            @endif
            

        </div>
        <h1>Paiements effectués au mois de {{$mois}} / {{$annee}}</h1>
    </div>

    <br>
    @if (count($data) > 0)
        
    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'">
        
        <thead class="bg-gray-50 dark:bg-white/5">
            <tr class="text-lg font-bold">
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Date de paiment
                </td>

                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Loyer payé
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Type payement
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
                $_id = 0;
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
                    {{$ly->garantie ? 'Avec garantie' : 'Sans garantie'}} 
                </td>
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$ly->occupation->montant - $total}} $
                </td>

                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$ly->observation ?? 'Aucune observation'}} 
                </td>

               {{--  <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    <x-filament::icon-button
                        icon="heroicon-s-eye"
                        tag="a"
                        label="Detail"
                        tooltip="Imprimer"
                        wire:click="imprimer({{$ly}})"
                    />
                </td> --}}
                
            </tr>
            @endforeach
            <tr class="text-lg font-bold bg-gray-50 dark:bg-transparent">
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">Total</td>
                <td>{{$total}} $</td>
            </tr>
        </tbody>

    
    </table>
    @else
    <div class="flex justify-center items-center" style="padding: 100px;">
        <h1>Aucun paiement effectué au cours de ce mois-ci !</h1>
    </div>
    @endif
    {{-- <div class="fixed top-32 right-20 bg-gray-200 text-white p-3 rounded-2xl">
        <x-filament::icon-button
            icon="heroicon-o-printer"
            tag="a"
            label="imprimer"
            tooltip="Imprimer"
            href="/storage/pdf/doc.pdf"
            target="_blank"
            size="xl"
            color="danger"
                
        />
    </div> --}}
</div>
