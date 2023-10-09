<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'desc'
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

    public function genres(): belongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genre');
    }
}
