<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class image extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "image_attraction_activities";
    protected $fillable = ['path_of_image'];
    public $timestamps = true;
}
