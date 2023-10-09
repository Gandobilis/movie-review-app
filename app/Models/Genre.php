<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'desc'
    ];

    public function users(): belongsToMany
    {
        return $this->belongsToMany(User::class, 'user_genre');
    }
}
