<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_name',
        'email',
        'shipping_cost',
        'no_telp',
        'whatsapp',
        'image_about',
        'about',
        'address',
        'google_map',
        'latitude',
        'longitude'
    ];
}
