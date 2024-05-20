<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class image_resturant extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "image_resturant";
    protected $fillable = ['path_of_image', 'resturant_id'];
    public $timestamps = true;
}
