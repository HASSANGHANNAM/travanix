<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class trip_has_place extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "trip_has_place";
    protected $fillable = ['attraction_activite_id', 'trip_id', 'resturant_id', 'hotel_id'];
    public $timestamps = true;
    public function trip()
    {
        return $this->belongsTo(trip::class);
    }
}
