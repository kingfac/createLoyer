
<div>

    {{-- @vite('resources/css/app.css') --}}
    <link rel="stylesheet" href="{{asset('build/assets/app-7c2ef0f7.css')}}">
    
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    {{$this->form}}
    {{-- {{$this->table}} --}}
   
    <br>

    <!-- component -->
    <div class="w-full" x-data="{recherche : '', clear(){this.recherche=''},}">

        <div class="flex flex-col">
            <div class="py-4 text-2xl bg-gray-100 px-4 flex justify-between dark:bg-transparent">
                <div>
                    @if (strlen($recherche) > 0)    
                    <span  class="text-xs p-1 bg-blue-300 rounded">
                        <span>{{$recherche}}</span>
                        <span >
                            <span class="cursor-pointer p-1 bg-blue-50" wire:click="clear()">x</span>
                        </span>
                    </span>
                    @endif
                </div>

                
                <div class="flex gap-5">

                    <div class="relative text-gray-600 focus-within:text-gray-400">
                      <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                        <button type="submit" class="p-1 focus:outline-none focus:shadow-outline">
                          <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                      </span>
                      <input type="search" name="q" 
                            {{-- x-model="recherche"  --}}
                            class="py-2 text-sm text-white  rounded-md pl-10 focus:outline-none focus:bg-white focus:text-gray-900" placeholder="Search..." autocomplete="off" 
                            wire:model.live="recherche">
                        
                    </div>
                    <div>
                        
                        <div class="flex justify-center">
                            <div
                                x-data="{
                                    open: false,
                                    toggle() {
                                        if (this.open) {
                                            return this.close()
                                        }

                                        this.$refs.button.focus()

                                        this.open = true
                                    },
                                    close(focusAfter) {
                                        if (! this.open) return

                                        this.open = false

                                        focusAfter && focusAfter.focus()
                                    }
                                }"
                                x-on:keydown.escape.prevent.stop="close($refs.button)"
                                x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                                x-id="['dropdown-button']"
                                class="relative"
                            >
                                <!-- Button -->
                                <button
                                    x-ref="button"
                                    x-on:click="toggle()"
                                    :aria-expanded="open"
                                    :aria-controls="$id('dropdown-button')"
                                    type="button"
                                    class="flex items-center   px-1 py-1 rounded-md "
                                >
                                    <!-- Heroicon: chevron-down -->
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                                    </svg>
                                </button>

                                <!-- Panel -->
                                <div
                                    x-ref="panel"
                                    x-show="open"
                                    x-transition.origin.top.left
                                    x-on:click.outside="close($refs.button)"
                                    :id="$id('dropdown-button')"
                                    style="display: none; width:300px; height:400px;"
                                    class="absolute right-0 mt-4  rounded-md bg-white shadow-md z-50 px-5 "
                                >
                                @php
                                    use App\Models\Galerie;
                                @endphp
                                    <div class="flex flex-col gap-4 py-4">
                                        <h1 class="text-lg font-bold">Filtres</h1>
                                        <div class="flex flex-col gap-1">
                                            <label for="galerie" class="text-sm">Galerie</label>
                                            <select data-te-select-init id="galerie" class="border rounded-lg text-lg px-3" wire:model.live="selectedGal">
                                                <option>Selectionner</option>
                                                @foreach (Galerie::all() as $gal)
                                                <option value="{{$gal->nom}}">{{$gal->nom}}</option>
                                                @endforeach
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               
            </div>
            <div class="overflow-x-auto shadow-md sm:rounded-lg">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="p-4">
                                        <div class="flex items-center">
                                            <div class="w-4 h-4 bg-blue-600 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 p-2"></div>
                                            <label for="checkbox-all" class="sr-only">checkbox</label>
                                        </div>
                                    </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        Locataire
                                    </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        Galerie
                                    </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        Occupation
                                    </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        Loyer 
                                    </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        Avances 
                                    </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        Reste
                                    </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        Date
                                    </th>
                                    <th scope="col" class="p-4">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">

                                @php
                                    $_id = 0;
                                    $ctrR = 0;
                                @endphp
                                @foreach ($data as $dt) 
                                @if ($_id != $dt->id )
                                @php
                                    $_id = $dt->id;
                                @endphp
                                
                                @if (strlen($recherche) > 0 )
                                @if (str_contains(strtoupper($dt->noms), strtoupper($recherche)) )
                                @php
                                        $ctrR = 0;
                                @endphp
                                
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="p-4 w-4">
                                        @if ($dt->somme == 0)
                                        <div class="w-4 h-4 bg-blue-600 rounded border-gray-300  p-2"></div>
                                        @else
                                            @if ($dt->occupation->montant == $dt->somme)
                                            <div class="w-4 h-4 bg-green-600 rounded border-gray-300  p-2"></div>
                                            @else
                                            <div class="w-4 h-4 bg-red-600 rounded border-gray-300  p-2"></div>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{$dt->noms}}
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-500 whitespace-nowrap dark:text-white">
                                        {{$dt->occupation->galerie->nom}}
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{$dt->occupation->typeOccu->nom}}
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{$dt->occupation->montant}}$
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{$dt->somme ?? 0}} $
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{$dt->occupation->montant - $dt->somme}} $
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{$dt->created_at ?? 'Aucun paiement'}}
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-right whitespace-nowrap flex gap-3">
                                        {{-- <x-filament::icon-button
                                            icon="heroicon-o-printer"
                                            tag="a"
                                            label="Detail"
                                            tooltip="Imprimer"
                                            wire:click="imprimer({{$dt->id}})"
                                        /> --}}
                                    <x-filament::icon-button
                                        icon="heroicon-s-eye"
                                        tag="a"
                                        label="Detail"
                                        tooltip="Voir le detail"
                                        wire:click="detail({{$dt->id}})"
                                    />
                                    </td>
                                </tr>
                                @else
                                    @php
                                        $ctrR += 1;
                                    @endphp
                                @endif
                                
                                @else    
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="p-4 w-4">
                                        @if ($dt->somme == 0)
                                        <div class="w-4 h-4 bg-red-600 rounded border-gray-300  p-2"></div>
                                        @else
                                            @if ($dt->occupation->montant == $dt->somme)
                                            <div class="w-4 h-4 bg-green-600 rounded border-gray-300  p-2"></div>
                                            @else
                                            <div class="w-4 h-4 bg-blue-600 rounded border-gray-300  p-2"></div>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{$dt->noms}}
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-500 whitespace-nowrap dark:text-white">
                                        {{$dt->occupation->galerie->nom}}
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{$dt->occupation->typeOccu->nom}}
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{$dt->occupation->montant}}$
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{$dt->somme ?? 0}} $
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{$dt->occupation->montant - $dt->somme}} $
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{$dt->created_at ?? 'Aucun paiement'}}
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-right whitespace-nowrap flex gap-3">
                                        {{-- <x-filament::icon-button
                                            icon="heroicon-o-printer"
                                            tag="a"
                                            label="Detail"
                                            tooltip="Imprimer"
                                            wire:click="imprimer({{$dt->id}})"
                                        /> --}}
                                    <x-filament::icon-button
                                        icon="heroicon-s-eye"
                                        tag="a"
                                        label="Detail"
                                        tooltip="Voir le detail"
                                        wire:click="detail({{$dt->id}})"
                                    />
                                    </td>
                                </tr>
                                @endif
                                @endif
                                @endforeach
                                @if ($ctrR > 0)
                                    <h1>Not found</h1>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
       
    <!-- Pagination navigation links -->
    {{-- {{ $data->links() }} --}}
    

    <x-filament::modal id="detail" width="7xl" slide-over :close-by-clicking-away="false">
    {{-- Modal content --}}
        @if ($dt1)
            <livewire:custom-create-loyer :locataire_id=$dt1 :mois=$mois :annee=$annee>
        @endif
</x-filament::modal>
@filamentScripts
{{-- @vite('resources/js/app.js') --}}
<script src="{{asset('build/assets/app-ddee773b.js')}}"></script>
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('openNewTab', function (data) {
            window.open(data.url, '_blank');
        });
    });
</script>
</div>
