<link rel="stylesheet" href="{{asset('build/assets/app-1a2e2064.css')}}">
<div>
    {{-- @vite('resources/css/app.css') --}}
 
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}

    <h1 class="text-2xl font-bold" style="padding-bottom: 25px;">Loyer total du mois de : {{ $mois }}</h1>
   
    @if (count($data))
    <div class="fi-wi-stats-overview">
        <div class="fi-wi-stats-overview-stats-ctn grid gap-6 md:grid-cols-3">
            @livewire('components.card-stat', [
                'valeur' => $prevu, 
                'label' => 'Montant prévu', 
                'description' => 'Le montant prévu du mois', 
                'color' => 'red'])
            @livewire('components.card-stat', [
                'valeur' => $recu, 
                'label' => 'Montant reçu', 
                'description' => 'Le montant total reçu ', 
                'color' => 'blue'])
            @livewire('components.card-stat', [
                'valeur' => $prevu-$recu, 
                'label' => 'Montant restant', 
                'description' => 'Le montant total restant', 
                'color' => 'green'])
        </div>
    </div>
    @endif
</div>
