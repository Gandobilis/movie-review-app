<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $connection = 'mysql2';
    protected $fillable = [
        'email',
        'active'
    ];
}
