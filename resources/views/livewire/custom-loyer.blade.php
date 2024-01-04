@props([
    'alpineHidden' => null,
    'alpineSelected' => null,
    'recordAction' => null,
    'recordUrl' => null,
    'striped' => false,
    'footer' => null,
    'header' => null,
    'reorderable' => false,
    'reorderAnimationDuration' => 300,
])

@php
    $hasAlpineHiddenClasses = filled($alpineHidden);
    $hasAlpineSelectedClasses = filled($alpineSelected);

    $stripedClasses = 'bg-gray-50 dark:bg-white/5';
@endphp


<div>
    <style>
        tbody tr:hover {
            background-color: #f4f4f5;
        }

        tbody tr {
            cursor: pointer;
            padding-top: 30px;
        }
    </style>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    {{$this->form}}
    <br>
    @if (count($data) > 0)
        
    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5'">
        
        <thead class="bg-gray-50 dark:bg-white/5">
            <tr class="text-lg font-bold">
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    E
                </td>                
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Locataire
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Galerie
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Type d'occupation
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Loyer mensuel,
                </td>

                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Avances payées
                </td>

                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Reste à payer
                </td>
                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    Date
                </td>
               
            </tr>
        </thead>
    

        <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
        
            @php
                $_id = 0;
            @endphp
            @foreach ($data as $dt) 
            @if ($_id != $dt->id )
            @php
                $_id = $dt->id;
            @endphp
            <tr class="hover:bg-white/5 dark:hover:bg-white/5 ">
                @if ($dt->somme == 0)
                    
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: red;">■</td>
                @else
                @if ($dt->occupation->montant == $dt->somme)
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: green;">■</td>
                @else
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3" style="color: blue;">■</td>       
                @endif 
                @endif
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->noms}}
                </td>
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->occupation->galerie->nom}}
                </td>
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->occupation->typeOccu->nom}}
                </td>
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->occupation->montant}}$
                </td>
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->somme ?? 0}} $
                </td>
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->occupation->montant - $dt->somme}} $
                </td>
                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    {{$dt->created_at ?? 'Aucun paiement'}}
                </td>

                <td class="fi-ta-cell  first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                    <div class="flex gap-2 py-2">
                        
                        <x-filament::icon-button
                            icon="heroicon-o-printer"
                            tag="a"
                            label="Detail"
                            tooltip="Imprimer"
                            wire:click="imprimer({{$dt->id}})"
                        />
                        <x-filament::icon-button
                            icon="heroicon-s-eye"
                            tag="a"
                            label="Detail"
                            tooltip="Voir le detail"
                            wire:click="detail({{$dt->id}})"
                        />
                    </div>
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>

    
    </table>
    <!-- Pagination navigation links -->
    {{-- {{ $data->links() }} --}}
    @endif

    <x-filament::modal id="detail" width="7xl" slide-over :close-by-clicking-away="false">
    {{-- Modal content --}}
        @if ($dt1)
            <livewire:custom-create-loyer :locataire_id=$dt1 :mois=$mois :annee=$annee>
        @endif
</x-filament::modal>

<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('openNewTab', function (data) {
            window.open(data.url, '_blank');
        });
    });
</script>
</div>
