<x-filament-panels::page>
    <x-filament::section>
        {{-- Widget content --}}
        <form wire:submit="create"> 
            {{ $this->form }}
 
            {{-- <x-filament::button type="submit" class="mt-3  py-5">
                {{ __('filament-panels::resources/pages/create-record.form.actions.create.label') }}
            </x-filament::button> --}}
            <div style="display: grid;
                grid-template-columns: repeat(4, 1fr);
                grid-gap: 3px;">

                <x-filament::button type="button" class="mt-3  py-5" wire:click="go(0)">
                    Locataires à jour
                </x-filament::button>

               {{--  <x-filament::button type="button" class="mt-3  py-5" wire:click="go(1)">
                    Locataires avec payement en retard
                </x-filament::button> --}}

                <x-filament::button type="button" class="mt-3  py-5" wire:click="go(2)">
                    Locataires avec payement partiel
                </x-filament::button>

                <x-filament::button type="button" class="mt-3  py-5" wire:click="go(3)">
                    Locataires avec soldes impayés
                </x-filament::button>

                <x-filament::button type="button" class="mt-3  py-5" wire:click="go(4)">
                    Evolution Loyer/Locataire
                </x-filament::button>

                <x-filament::button type="button" class="mt-3  py-5 bg-danger" wire:click="go(5)">
                    Loyer total
                </x-filament::button>

                
            </div>
        </form> 
    </x-filament::section>
    <x-filament::section>
        @if ($menu == $menus[0])
            <livewire:loc-ajour :mois=$mois :annee=$annee>
        @endif

        @if ($menu == $menus[1])
            <livewire:loc-paie-retard :mois=$mois :annee=$annee>
        @endif

        @if ($menu == $menus[2])
            <livewire:loc-paie-partiel :mois=$mois :annee=$annee>
        @endif

        @if ($menu == $menus[3])
            <livewire:loc-solde-impaye :mois=$mois :annee=$annee>
        @endif
        
        @if ($menu == $menus[4])
        <livewire:filter-loyer :mois=$mois :annee=$annee>
        @endif
        @if ($menu == $menus[5])
            <livewire:loc-loyer-total :mois=$mois :annee=$annee>
        @endif
    </x-filament::section>
</x-filament-panels::page>
