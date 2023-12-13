<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Locataire extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'postnom',  'prenom', 'tel', 'garantie', 'occupation_id','actif'];
    protected $casts = [
        'nom' => 'string', 
        'postnom' => 'string', 
        'prenom' => 'string', 
        'tel' => 'string', 
        'garantie' => 'double', 
        'occupation_id' => 'integer',
        'actif' => 'boolean'
    ];

    public function occupation() : BelongsTo {
        return $this->belongsTo(Occupation::class);
    }

    public function loyers() : HasMany {
        return $this->hasMany(Loyer::class);
    }
}
