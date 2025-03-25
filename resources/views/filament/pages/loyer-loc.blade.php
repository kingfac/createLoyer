

<x-filament-panels::page >
    <x-filament::breadcrumbs :breadcrumbs="[
        '/' => 'Dashboard',
        '/loyer-loc' => 'Loyer',

    ]" />
    @livewire('custom-loyer')

</x-filament-panels::page>
