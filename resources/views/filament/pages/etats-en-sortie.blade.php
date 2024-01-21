<x-filament-panels::page>
    <x-filament::breadcrumbs :breadcrumbs="[
        '/' => 'Dashboard',
        '/etats-en-sortie' => 'Etat en sortie',
        
    ]" />
    <x-filament::section>
        {{-- Widget content --}}
        <form wire:submit="create"> 
            {{ $this->form }}
 
            {{-- <x-filament::button type="submit" class="mt-3  py-5">
                {{ __('filament-panels::resources/pages/create-record.form.actions.create.label') }}
            </x-filament::button> --}}
            
            <div style="display: grid; grid-template-columns: repeat(4, 1fr);" class="gap-4">

                <x-filament::button type="button" class="mt-3  py-5 text-white hover:text-black" wire:click="go(0)" outlined>
                    Locataires à jour
                </x-filament::button>

               {{--  <x-filament::button type="button" class="mt-3  py-5" wire:click="go(1)">
                    Locataires avec payement en retard
                </x-filament::button> --}}

                <x-filament::button type="button" class="mt-3  py-5 text-white hover:text-black" wire:click="go(2)" outlined>
                    Locataires avec payement partiel
                </x-filament::button>

                <x-filament::button type="button" class="mt-3  py-5 text-white hover:text-black" wire:click="go(3)" outlined>
                    Locataires avec soldes impayés
                </x-filament::button>

                <x-filament::button type="button" class="mt-3  py-5 text-white hover:text-black" wire:click="go(4)" outlined>
                    Evolution Loyer/Locataire
                </x-filament::button>

                <x-filament::button type="button" class="mt-3  py-5 bg-danger text-white hover:text-black" wire:click="go(5)" outlined>
                    Situation des paiements Global
                </x-filament::button>
                <x-filament::button type="button" class="mt-3  py-5 bg-danger text-white hover:text-black" wire:click="go(6)" outlined>
                    Paiements Journalier
                </x-filament::button>
               {{--  <x-filament::button type="button" class="mt-3  py-5 bg-danger text-white hover:text-black" wire:click="go(11)" outlined>
                    Rapport Mensuel
                </x-filament::button> --}}

                
            </div>
        </form> 
    </x-filament::section>
    <x-filament::section>
        
        @if ($menu == $menus[0])
            <p>cool</p>
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
        @if ($menu == $menus[6])
            <livewire:paie-journalier>
        @endif
        @if ($menu == $menus[11])
            <livewire:rapport-mensuel>
        @endif
    </x-filament::section>
</x-filament-panels::page>
