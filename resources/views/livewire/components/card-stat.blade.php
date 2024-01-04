<div class="grid gap-y-2" style=" box-shadow:1px 1px 6px 1px gray; border-bottom:5px solid {{ $color }}; padding:40px 20px 40px 20px;">
    <div class="flex items-center gap-x-2">
       {{--  <x-filament::icon
            :icon="'x-heroicon-s-heart'"
            class="fi-wi-stats-overview-stat-icon h-5 w-5 text-gray-400 dark:text-gray-500"
        /> --}}

        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
            {{ $label }}
        </span>
    </div>

    <div
        class="text-3xl font-semibold tracking-tight text-gray-950 dark:text-white"
    >
        {{ $valeur }}
    </div>

        <div class="flex items-center gap-x-1">
            {{-- <x-filament::icon
                :icon="'x-heroicon-s-heart'"/> --}}

            <span
                class = "fi-wi-stats-overview-stat-description text-sm fi-color-gray text-gray-500 dark:text-gray-400"
            >
                {{ $description }}
            </span>

            
        </div>
    
</div>