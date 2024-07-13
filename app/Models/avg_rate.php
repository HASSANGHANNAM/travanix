<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class avg_rate extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "avg_rate";
    protected $fillable = ['hotel_id', 'attraction_activities_id', 'resturant_id', 'trip_id', 'count', 'avg'];
    public $timestamps = true;
}
