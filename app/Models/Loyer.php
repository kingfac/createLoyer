<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loyer extends Model
{
    use HasFactory;

    protected $fillable = ['mois', 'annee', 'montant', 'locataire_id','garantie', 'observation'];

    protected $casts = [
        'mois' => 'string', 
        'annee' => 'integer', 
        'montant' => 'double', 
        'locataire_id' => 'integer',
        'garantie' => 'boolean',
        'observation' => 'string', 
        'created_at' => 'datetime'
    ];

    public function locataire() : BelongsTo {
        return $this->belongsTo(Locataire::class);
    }
}
