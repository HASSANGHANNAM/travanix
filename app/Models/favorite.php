<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class favorite extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "favorite";
    protected $fillable = ['tourist_id', 'trip_id', 'attraction_activity_id', 'resturant_id', 'hotel_id'];
    public $timestamps = true;
    public function trip()
    {
        return $this->belongsTo(trip::class);
    }
    public function attraction_activity()
    {
        return $this->belongsTo(attraction_activity::class);
    }
    public function resturant()
    {
        return $this->belongsTo(resturant::class);
    }
    public function hotel()
    {
        return $this->belongsTo(hotel::class);
    }
}
