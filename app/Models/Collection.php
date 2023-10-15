<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collection extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_collection');
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_collection');
    }
}
