<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Divers extends Model
{
    use HasFactory;

    protected $fillable = ['locataire_id','besoin','qte','cu', 'entreprise'];
    
    public function locataire() : BelongsTo{
        return $this->belongsTo(Locataire::class);
    }
}
