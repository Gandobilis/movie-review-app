<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function genres(): belongsToMany
    {
        return $this->belongsToMany(Genre::class, 'user_genre');
    }

    public function created_collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }

    public function liked_collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'user_collection');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function viewed_movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'user_movie');
    }
}
