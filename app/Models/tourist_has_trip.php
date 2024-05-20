<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class tourist_has_trip extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "tourist_has_trip";
    protected $fillable = ['tourist_id', 'trip_id', 'payment_status', 'number_of_seat'];
    public $timestamps = true;
}
