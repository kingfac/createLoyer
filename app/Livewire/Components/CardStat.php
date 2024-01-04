<?php

namespace App\Livewire\Components;

use Livewire\Component;

class CardStat extends Component
{
    public $valeur;
    public $description;
    public $label;
    public $color;
    
    public function render()
    {
        return view('livewire.components.card-stat');
    }
}
