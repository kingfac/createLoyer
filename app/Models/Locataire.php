<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Locataire extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'postnom',  'prenom', 'tel', 'garantie', 'occupation_id','actif', 'nbr', 'mp', 'ap', 'num_occupation'];
    protected $casts = [
        'nom' => 'string', 
        'postnom' => 'string', 
        'prenom' => 'string', 
        'tel' => 'string', 
        'garantie' => 'double', 
        'nbr' => 'double', 
        'occupation_id' => 'integer',
        'mp' => 'integer',
        'ap' => 'integer',
        'actif' => 'boolean',
        'num_occupation' => 'string'
    ];

    public function occupation() : BelongsTo {
        return $this->belongsTo(Occupation::class);
    }

    public function loyers() : HasMany {
        return $this->hasMany(Loyer::class);
    }
    
    public function garanties() : HasMany {
        return $this->hasMany(Garantie::class);
    }

    public function divers() : HasMany {
        return $this->hasMany(Divers::class);
    }
    /* public function noms(){
        return $this->nom.' - '.$this->prenom;
    } */
}
