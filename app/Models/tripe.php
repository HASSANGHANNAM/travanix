<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class tripe extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "trip";
    protected $fillable = ['type', 'type_of_trip', 'reviews_about_trip', 'price_trip'];
    public $timestamps = true;
}
