<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class rating_resturant extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "rating_resturant";
    protected $fillable = ['rate', 'resturant_id'];
    public $timestamps = true;
}
