<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Garantie extends Model
{
    use HasFactory;

    protected $fillable = ['montant','restitution','locataire_id','users_id'];


    public function locataire(): BelongsTo {
        return $this->belongsTo(Locataire::class);
    }

    public function locs(){
        return Locataire::where('actif', true);
    }
}
