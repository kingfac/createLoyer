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
    @filamentStyles
    @vite('resources/css/app.css')
    
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    {{$this->form}}
    {{$this->table}}
    <div class="bg-red-500 p-10">
        <p class="text-2xl">glodi</p>
    </div>
    <br>
    
        
    <table class="table-auto border-collapse border border-slate-400 w-full">
        <caption class="caption-top">
            Table 3.1: Professional wrestlers and their signature moves.
          </caption>
        <thead class="">
            <tr class="">
                <th class="">
                    E
                </th>                
                <th class="">
                    Locataire
                </th>
                <th class="">
                    Galerie
                </th>
                <th class="">
                    Type d'occupation
                </th>
                <th class="">
                    Loyer mensuel,
                </th>

                <th class="">
                    Avances payées
                </th>

                <th class="">
                    Reste à payer
                </th>
                <th class="">
                    Date
                </th>
               
            </tr>
        </thead>
    

        <tbody class="">
        
            @php
                $_id = 0;
            @endphp
            @foreach ($data as $dt) 
            @if ($_id != $dt->id )
            @php
                $_id = $dt->id;
            @endphp
            <tr class="hover:bg-slate-200">
                @if ($dt->somme == 0)
                    
                <td class="" style="color: red;">■</td>
                @else
                @if ($dt->occupation->montant == $dt->somme)
                <td class="" style="color: green;">■</td>
                @else
                <td class="" style="color: blue;">■</td>       
                @endif 
                @endif
                <td class="">
                    {{$dt->noms}}
                </td>
                <td class="">
                    {{$dt->occupation->galerie->nom}}
                </td>
                <td class="">
                    {{$dt->occupation->typeOccu->nom}}
                </td>
                <td class="">
                    {{$dt->occupation->montant}}$
                </td>
                <td class="">
                    {{$dt->somme ?? 0}} $
                </td>
                <td class="">
                    {{$dt->occupation->montant - $dt->somme}} $
                </td>
                <td class="">
                    {{$dt->created_at ?? 'Aucun paiement'}}
                </td>

                <td class="">
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
    

    <x-filament::modal id="detail" width="7xl" slide-over :close-by-clicking-away="false">
    {{-- Modal content --}}
        @if ($dt1)
            <livewire:custom-create-loyer :locataire_id=$dt1 :mois=$mois :annee=$annee>
        @endif
</x-filament::modal>
@filamentScripts
@vite('resources/js/app.js')
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('openNewTab', function (data) {
            window.open(data.url, '_blank');
        });
    });
</script>
</div>
