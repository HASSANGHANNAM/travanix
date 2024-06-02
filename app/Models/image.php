<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class image extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "image";
    protected $fillable = ['attraction_activity_id', 'resturant_id', 'hotel_id', 'path_of_image'];
    public $timestamps = true;
}
