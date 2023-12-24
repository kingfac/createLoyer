<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Occupation extends Model
{
    use HasFactory;
    protected $fillable = ['ref', 'montant', 'multiple', 'actif', 'galerie_id', 'type_occu_id'];

    protected $casts = [
        'ref' => 'string', 
        'montant' => 'double', 
        'multiple' => 'boolean', 
        'actif' => 'boolean', 
        'galerie_id' => 'integer', 
        'type_occu_id' => 'integer'
    ];

    public function galerie() : BelongsTo {
        return $this->belongsTo(Galerie::class);
    }

    public function typeOccu() : BelongsTo {
        return $this->belongsTo(TypeOccu::class);
    }

    public function locataires(): HasMany{
        return $this->hasMany(Locataire::class);
    }
}
