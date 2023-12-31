<link rel="stylesheet" href="{{asset('build/assets/app-514a0b6d.css')}}">
@php
    use App\Models\Loyer;
@endphp
<x-filament-panels::page>
    <x-filament::page>
    <x-filament::section>
    {{-- {{$data}} --}}
    <div class="flex justify-between">
        <h1 class="text-2xl font-bold">Evolution loyer du mois de {{$mois}}</h1>
        <p class="font-bold">Total locataires : {{$data->count()}}</p>
    </div>
    <div style="display: grid;
        grid-template-columns: repeat(4, 1fr);" class="gap-5">

    
    @foreach ($data as $loc)
        @php
            $somme = Loyer::where(['locataire_id' => $loc->id, 'mois' => $mois])->sum('montant');
            $loyer = $loc->occupation->montant;
        @endphp
        <div {{-- style="background-color: {{ ($somme == $loyer ? '#dcfce7' : ($somme !=0 ? '#fef9c3' : '#fee2e2'))}};" --}} class="p-4 ml-2 rounded-lg bg-gray-200">
            <b class="px-2 py-1 bg-white rounded-lg" >{{$loop->index + 1}}</b>
            <span class="px-2 text-lg font-bold">Locataire {{$loc->nom}} </span>
            
            <div class="flex justify-center {{-- items-center text-center --}}">
                <p class="py-2 font-bold text-xl">{{$somme}}$ / {{$loyer}}$</p>
            </div>
        </div>
        @endforeach
    </div>
    </x-filament::section>
    </x-filament::page>
</x-filament-panels::page>
