<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviewrating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'comments',
        'star_rating',
        'status',
    ];
}
