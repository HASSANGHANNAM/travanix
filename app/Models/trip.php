<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class trip extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "trip";
    protected $fillable = ['type_of_trip', 'reviews_about_trip', 'price_trip', 'trip_end_time', 'trip_start_time', 'number_of_allSeat'];
    public $timestamps = true;
    public function places()
    {
        return $this->hasMany(trip_has_place::class);
    }
}
