<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class image extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "image_hotel";
    protected $fillable = ['path_of_image', 'type', 'type_id'];
    public $timestamps = true;
}
