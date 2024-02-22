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
            
            <div style="display: grid; grid-template-columns: repeat(5, 1fr);" class="gap-4" 

            {{-- alpine js code --}}
            x-data="{
                a:555
            }">

                <button 
                    size="xs" 
                    icon="heroicon-m-sparkles" 
                    type="button" 
                    class="mt-3  py-5 text-white hover:text-black bg-blue-600 rounded-lg hover:bg-gray-100  border-2 flex justify-center items-center gap-3" 
                    wire:click="go(0)"
                    :class="{'bg-red-800 text-black':a==0}"
                    @click="a=0"
                    outlined>
                    <x-heroicon-s-sparkles class="w-4 h-4" />
                    Locataires à jour
                </button>

               {{--  <x-filament::button type="button" class="mt-3  py-5" wire:click="go(1)">
                    Locataires avec payement en retard
                </x-filament::button> --}}
                <button 
                    size="xs" 
                    icon="heroicon-m-sparkles" 
                    type="button" 
                    class="mt-3  py-5 text-white hover:text-black bg-blue-600 rounded-lg hover:bg-gray-100  border-2 flex justify-center items-center gap-3"
                    :class="{'bg-red-800 text-black':a==2}"
                    @click="a=2"
                    wire:click="go(2)" outlined>
                    <x-heroicon-s-sparkles class="w-4 h-4" />
                    Payement partiel
                </button>

                <button 
                    size="xs" 
                    icon="heroicon-m-sparkles" 
                    type="button" 
                    class="mt-3  py-5 text-white hover:text-black bg-blue-600 rounded-lg hover:bg-gray-100  border-2 flex justify-center items-center gap-3" 
                    wire:click="go(3)" 
                    :class="{'bg-red-800 text-black':a==3}"
                    @click="a=3"
                    outlined>
                    <x-heroicon-s-sparkles class="w-4 h-4" />
                    Soldes impayés
                </button>

                <button 
                    size="xs" 
                    icon="heroicon-m-sparkles" 
                    type="button" 
                    class="mt-3  py-5 text-white hover:text-black bg-blue-600 rounded-lg hover:bg-gray-100  border-2 flex justify-center items-center gap-3" 
                    wire:click="go(4)" 
                    :class="{'bg-red-800 text-black':a==4}"
                    @click="a=4"
                    outlined>
                    <x-heroicon-s-sparkles class="w-4 h-4" />
                    Evolution loyer
                </button>

                <button 
                    size="xs" 
                    icon="heroicon-m-sparkles" 
                    type="button" 
                    class="mt-3  py-5 text-white hover:text-black bg-blue-600 rounded-lg hover:bg-gray-100  border-2 flex justify-center items-center gap-3" 
                    wire:click="go(5)"
                    :class="{'bg-red-800 text-black':a==5}"
                    @click="a=5"
                    outlined>
                    <x-heroicon-s-sparkles class="w-4 h-4" />
                    Paiements Global
                </button>
                <button 
                    size="xs" 
                    icon="heroicon-m-sparkles" 
                    type="button" class="mt-3  py-5 text-white hover:text-black bg-blue-600 rounded-lg hover:bg-gray-100  border-2 flex justify-center items-center gap-3" 
                    wire:click="go(6)" 
                    :class="{'bg-red-800 text-black':a==6}"
                    @click="a=6"
                    outlined>
                    <x-heroicon-s-sparkles class="w-4 h-4" />
                    Paiements Journalier
                </button>
                <button 
                    size="xs" 
                    icon="heroicon-m-sparkles" 
                    type="button" 
                    class="mt-3  py-5 text-white hover:text-black bg-blue-600 rounded-lg hover:bg-gray-100  border-2 flex justify-center items-center gap-3" 
                    wire:click="go(11)"
                    :class="{'bg-red-800 text-black':a==11}"
                    @click="a=11"
                    outlined>
                    <x-heroicon-s-sparkles class="w-4 h-4" />
                    Rapport Mensuel
                </button>

                <button 
                    size="xs" 
                    icon="heroicon-m-sparkles" 
                    type="button" class="mt-3  py-5 text-white hover:text-black bg-blue-600 rounded-lg hover:bg-gray-100  border-2 flex justify-center items-center gap-3" 
                    wire:click="go(7)" 
                    :class="{'bg-red-800 text-black':a==7}"
                    @click="a=7"
                    outlined>
                    <x-heroicon-s-sparkles class="w-4 h-4" />
                    Total garantie
                </button>

                <button 
                    size="xs" 
                    icon="heroicon-m-sparkles" 
                    type="button" 
                    class="mt-3  py-5 text-white hover:text-black bg-blue-600 rounded-lg hover:bg-gray-100  border-2 flex justify-center items-center gap-3" 
                    wire:click="go(8)" 
                    :class="{'bg-red-800 text-black':a==8}"
                    @click="a=8"
                    outlined>
                    <x-heroicon-s-sparkles class="w-4 h-4" />
                    Total divers
                </button>

                <button 
                    size="xs" 
                    icon="heroicon-m-sparkles" 
                    type="button" 
                    class="mt-3  py-5 text-white hover:text-black bg-blue-600 rounded-lg hover:bg-gray-100  border-2 flex justify-center items-center gap-3" 
                    wire:click="go(9)"
                    :class="{'bg-red-800 text-black':a==9}"
                    @click="a=9"
                    outlined>
                    <x-heroicon-s-sparkles class="w-4 h-4" />
                    Arriérés
                </button>
                <button 
                    size="xs" 
                    icon="heroicon-m-sparkles" 
                    type="button" 
                    class="mt-3  py-5 text-white hover:text-black bg-blue-600 rounded-lg hover:bg-gray-100  border-2 flex justify-center items-center gap-3" 
                    wire:click="go(10)"
                    :class="{'bg-red-800 text-black':a==10}"
                    @click="a=10"
                    outlined>
                    <x-heroicon-s-sparkles class="w-4 h-4" />
                    Prevision mensuelle
                </button>
                <button 
                    size="xs" 
                    icon="heroicon-m-sparkles" 
                    type="button" 
                    class="mt-3  py-5 text-white hover:text-black bg-blue-600 rounded-lg hover:bg-gray-100  border-2 flex justify-center items-center gap-3" 
                    wire:click="go(12)"
                    :class="{'bg-red-800 text-black':a==12}"
                    @click="a=12"

                    outlined>
                    <x-heroicon-s-sparkles class="w-4 h-4" />
                    Sorties avec dettes
                </button>
                <button 
                    size="xs" 
                    icon="heroicon-m-sparkles" 
                    type="button" 
                    class="mt-3  py-5 text-white hover:text-black bg-blue-600 rounded-lg hover:bg-gray-100  border-2 flex justify-center items-center gap-3" 
                    wire:click="go(14)"
                    :class="{'bg-red-800 text-black':a==14}" 
                    @click="a=14"

                    outlined>
                    <x-heroicon-s-sparkles class="w-4 h-4" />
                    Résumé Journalier
                </button>
                <button 
                    size="xs" 
                    icon="heroicon-m-sparkles" 
                    type="button" 
                    class="mt-3  py-5 text-white hover:text-black bg-blue-600 rounded-lg hover:bg-gray-100  border-2 flex justify-center items-center gap-3" 
                    wire:click="go(13)"
                    :class="{'bg-red-800 text-black':a==13}"
                    @click="a=13"
                    outlined>
                    <x-heroicon-s-sparkles class="w-4 h-4" />
                    Rapport Journalier
                </button>
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
        @if ($menu == $menus[6])
            <livewire:paie-journalier>
        @endif
        @if ($menu == $menus[7])
            <livewire:total-garantie>
        @endif
        @if ($menu == $menus[8])
            <livewire:diver-locataire>
        @endif
        @if ($menu == $menus[11])
            <livewire:rapport-mensuel :mois=$mois :annee=$annee>
        @endif
        @if ($menu == $menus[9])
            <livewire:loc-arrieres>
        @endif
        @if ($menu == $menus[10])
            <livewire:prev-mens :mois=$mois :annee=$annee>
        @endif
        @if ($menu == $menus[12])
            <livewire:sortie-dette :mois=$mois :annee=$annee>
        @endif
        @if ($menu == $menus[14])
            <livewire:resume-journalier :mois=$mois :annee=$annee>
        @endif
        @if ($menu == $menus[13])
            <livewire:rapport-journalier :mois=$mois :annee=$annee>
        @endif
    </x-filament::section>
</x-filament-panels::page>
