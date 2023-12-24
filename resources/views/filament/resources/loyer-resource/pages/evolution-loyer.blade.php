@php
    use App\Models\Loyer;
@endphp
<x-filament-panels::page>
    {{-- {{$data}} --}}
    <div class="flex justify-between">
        <h1 class="text-2xl font-bold">Evolution loyer du mois de {{$mois}}</h1>
        <p class="font-bold">Total locataires : {{$data->count()}}</p>
    </div>
    <div style="display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-gap: 3px;">

    
    @foreach ($data as $loc)
        @php
            $somme = Loyer::where(['locataire_id' => $loc->id, 'mois' => $mois])->sum('montant');
            $loyer = $loc->occupation->montant;
        @endphp
        <div style="background-color: {{ ($somme == $loyer ? '#dcfce7' : ($somme !=0 ? '#fef9c3' : '#fee2e2'))}};" class="p-4">
            <b class="p-2" style="background-color: white">{{$loop->index + 1}}</b>
            <span class="px-2 text-lg font-bold">Locataire {{$loc->nom}} </span>
            
            <div class="flex justify-between">
                <p class="py-2">Loyer à payer : {{$loyer}}$</p>
                <p class="py-2">Loyer payé : {{$somme}}$</p>
            </div>
        </div>
        @endforeach
    </div>
    
</x-filament-panels::page>
