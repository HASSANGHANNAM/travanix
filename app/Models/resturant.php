<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class resturant extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "resturant";
    protected $fillable = ['location_id', 'rating_id'];
    public $timestamps = true;
}
