
<div class="gap-y-2 shadow py-8 px-5 flex justify-between items-center bg-gray-50 dark:bg-gray-700">
    <link rel="stylesheet" href="{{asset('build/assets/app-3e76f9e4.css') }}">
    {{-- @vite('resources/css/app.css') --}}
    <div class="flex flex-col gap-3">
        <div class="flex items-center gap-x-2">
           {{--  <x-filament::icon
                :icon="'x-heroicon-s-heart'"
                class="fi-wi-stats-overview-stat-icon h-5 w-5 text-gray-400 dark:text-gray-500"
            /> --}}
    
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                {{ $label }}
            </span>
        </div>
    
        <div class="text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
            {{ $valeur }}
        </div>
        <div class="flex items-center gap-x-1">
            {{-- <x-filament::icon :icon="'x-heroicon-s-heart'"/> --}}
            <span class = "fi-wi-stats-overview-stat-description text-sm fi-color-gray text-gray-500 dark:text-gray-400">
                    {{ $description }}
            </span>    
        </div>
    </div>
    <div class="border-l pl-10 pr-5">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" w-16 h-16 text-{{$color}}-400">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
          
          
    </div>
</div>