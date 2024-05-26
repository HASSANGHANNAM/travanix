<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class image_attraction_activities extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "image_attraction_activities";
    protected $fillable = ['path_of_image', 'attraction_activity_id'];
    public $timestamps = true;
}
