<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <div class="flex  justify-between">
        @php
            $lelo = new DateTime('now');
            $lelo = $lelo->format('d-m-Y');
        @endphp
        <h1 class="text-2xl font-bold" style="padding-bottom: 25px;">Paiement journalier du {{$lelo}}</h1>
        {{-- <x-filament::icon-button
            icon="heroicon-o-printer"
            tag="a"
            label="imprimer"
            tooltip="Imprimer"
            href="/storage/pdf/doc.pdf"
            target="_blank"
            
        /> --}}
    </div>
    {{$this->table}}

    <link rel="stylesheet" href="{{public_path('css.css')}}"> 

<div class="">

    @php
        use App\Models\Loyer;
        use App\Models\User;
        use App\Models\Divers;
        use App\Models\Garantie;
    @endphp
    
    


    <div class="w-full" style=" text-align:right; margin-top:30px;">
        <p>Aujourd'hui le, {{$lelo}}</p>
    </div>
</div>
</div>
