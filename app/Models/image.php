<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class image extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "image";
    protected $fillable = ['path_of_image', 'type', 'type_id', 'hotel_id', 'resturant_id', 'attraction_activities_id'];
    public $timestamps = true;
}
