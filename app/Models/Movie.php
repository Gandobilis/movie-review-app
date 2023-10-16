<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'description'
    ];

    protected $appends = [
        'full_image'
    ];

    protected function fullImage(): Attribute
    {
        return Attribute::make(
            get: fn() => url('/') . '/storage/' . $this->image
        );
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genre');
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'movie_collection');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function viewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_movie');
    }
}
