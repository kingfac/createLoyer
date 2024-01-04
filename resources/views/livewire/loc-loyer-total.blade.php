<div>
 
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}

    <h1 class="text-2xl font-bold" style="padding-bottom: 25px;">Loyer total du mois de : {{ $mois }}</h1>
   
    @if (count($data))
    <div class="fi-wi-stats-overview">
        <div class="fi-wi-stats-overview-stats-ctn grid gap-6 md:grid-cols-3">
            @livewire('components.card-stat', ['valeur' => $prevu, 'label' => 'Montant prévu', 'description' => 'lihdhhdh', 'color' => 'red'])
            @livewire('components.card-stat', ['valeur' => $recu, 'label' => 'Montant reçu', 'description' => 'lihdhhdh', 'color' => 'orange'])
            @livewire('components.card-stat', ['valeur' => $prevu-$recu, 'label' => 'Montant restant', 'description' => 'lihdhhdh', 'color' => 'blue'])
        </div>
    </div>
    @endif
</div>
