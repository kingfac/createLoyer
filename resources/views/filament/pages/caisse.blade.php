<x-filament-panels::page>
    <x-filament::section>
        {{$this->form}}
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
                Grande caisse~
            </button>

            <button 
                size="xs" 
                icon="heroicon-m-sparkles" 
                type="button" 
                class="mt-3  py-5 text-white hover:text-black bg-blue-600 rounded-lg hover:bg-gray-100  border-2 flex justify-center items-center gap-3" 
                wire:click="go(1)"
                :class="{'bg-red-800 text-black':a==1}"
                @click="a=1"
                outlined>
                <x-heroicon-s-sparkles class="w-4 h-4" />
                Petite caisse
            </button>
        </div>
    </x-filament::section>

    <x-filament::section>
        
        @if ($menu == $menus[0])
            <livewire:grande-caisse> {{-- :mois=$mois :annee=$annee --}}
        @endif

        @if ($menu == $menus[1])
            <livewire:petite-caisse>
        @endif
    </x-filament::section>

</x-filament-panels::page>
