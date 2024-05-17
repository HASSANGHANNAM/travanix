<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class hotel extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "hotel";
    protected $fillable = ['rating_id', 'location_id', 'reviews_about_hotel', 'simple_description_about_hotel', 'hotel_name'];
    public $timestamps = true;
}
