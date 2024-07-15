<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class trip extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "trip";
    protected $fillable = ['trip_name', 'type_of_trip',  'price_trip', 'trip_end_time', 'trip_start_time', 'number_of_allSeat', 'location_id', 'description'];
    public $timestamps = true;
    public function places()
    {
        return $this->hasMany(trip_has_place::class);
    }
    public function location()
    {
        return $this->belongsTo(location::class);
    }
    public function avg_rate()
    {
        return $this->hasOne(avg_rate::class);
    }
}
