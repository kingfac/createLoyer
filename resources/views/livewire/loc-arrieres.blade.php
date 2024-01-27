<div>
    {{-- Success is as dangerous as failure. --}}
    @php
        $_id = 0;
        $ctrR = 0;
    @endphp
                        
    @foreach ($arrieres as $ar)
        @if ($_id != $ar->id)
            @php
                $_id = $ar->id;
                $ctrR += 1;
            @endphp
        <p>{{$ar->noms}} | {{$ar->montant ?? 0}} {{$ar->mois}} {{$ar->annee}}</p>
        @endif
        tt
    @endforeach
</div>
