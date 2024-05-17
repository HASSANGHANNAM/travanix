<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class trip_has_blace extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "trip_has_blace";
    protected $fillable = ['resturant_id', 'attraction_activities_id', 'hotel_id', 'trip_id'];
    public $timestamps = true;
}
