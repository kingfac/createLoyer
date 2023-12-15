<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commune extends Model
{
    use HasFactory;

    protected $fillable = ['nom'];

    protected $casts = ['nom' => 'string'];

    public function galeries() : HasMany {
        return $this->hasMany(Galerie::class);
    }
}
