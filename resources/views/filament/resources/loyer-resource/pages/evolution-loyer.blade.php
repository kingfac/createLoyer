{{-- <link rel="stylesheet" href="{{asset('build/assets/app-514a0b6d.css')}}"> --}}
@php
    use App\Models\Loyer;
@endphp
{{--@vite('resources/css/app.css')--}}
{{-- <link rel="stylesheet" href="{{asset('build/assets/app-247549ac.css')}}"> --}}
<x-filament-panels::page>
    
    <x-filament::breadcrumbs :breadcrumbs="[
        '/loyer-loc' => 'Loyer',
        '/loyer/janvier/evolution' => 'Evolution',
        
    ]" />
    {{-- {{$data}} --}}
    <div class="flex justify-between">
        <h1 class="text-2xl font-bold">Evolution loyer de {{$mois}} {{$annee}}</h1>
        <p class="font-bold">Total locataires : {{$data->count()}}</p>
    </div>
    <div style="" class="grid grid-cols-4 gap-5">

    
    @foreach ($data as $loc)
        @php
            $somme = Loyer::where(['locataire_id' => $loc->id, 'mois' => $mois])->sum('montant');
            $loyer = $loc->occupation->montant;
            $color = ($somme == $loyer ? 'green' : ($somme !=0 ? 'blue' : 'red'));
        @endphp
        <div style="" class="p-4 bg-{{$color}}-600 rounded-lg shadow-xl hover:bg-{{$color}}-50">
            <b class="px-2 py-1 rounded-lg bg-white text-black shadow-lg" >{{$loop->index + 1}}</b>
            <span class="px-2 text-lg font-bold text-white">Locataire {{$loc->nom}} </span>
            
            <div class="flex justify-center {{-- items-center text-center --}}">
                <p class="py-2 font-bold text-xl text-white">{{$somme}}$ / {{$loyer}}$</p>
            </div>
        </div>
        @endforeach
    </div>
    
</x-filament-panels::page>
