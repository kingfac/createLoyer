<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Galerie extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'av', 'num', 'commune_id', 'actif'];

    protected $casts = [
        'nom' => 'string',
        'av' => 'string',
        'num' => 'string',
        'commune_id' => 'integer'
    ];

    public function commune() : BelongsTo{
        return $this->belongsTo(Commune::class);
    }

    public function occupations(): HasMany{
        return $this->hasMany(Occupation::class);
    }
}
