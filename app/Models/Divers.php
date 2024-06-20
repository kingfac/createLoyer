<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Divers extends Model
{
    use HasFactory;

    protected $fillable = ['locataire_id','besoin','qte','cu', 'entreprise','users_id'];
    
    public function locataire() : BelongsTo{
        return $this->belongsTo(Locataire::class);
    }

    public function user() : BelongsTo{
        return $this->belongsTo(User::class);
    }
}
