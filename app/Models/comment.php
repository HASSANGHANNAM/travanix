<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class comment extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "comment";
    protected $fillable = ['attraction_activity_id', 'resturant_id', 'hotel_id', 'trip_id', 'comment', 'tourist_id'];
    public $timestamps = true;
    public function hotel()
    {
        return $this->belongsTo(hotel::class, 'hotel_id');
    }

    public function rate()
    {
        return $this->hasOne(rate::class, 'tourist_id', 'tourist_id');
    }
}
