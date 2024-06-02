<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class room extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "room";
    protected $fillable = ['size_room', 'size_of_bed', 'capacity_room', 'price_room', 'available_services', 'hotel_id', 'start_reservation', 'end_reservation'];
    public $timestamps = true;
}
