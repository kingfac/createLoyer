<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Depense extends Model
{
    use HasFactory;

    protected $fillable = ['besoin','qte','cu', 'users_id','observation'];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }
}
