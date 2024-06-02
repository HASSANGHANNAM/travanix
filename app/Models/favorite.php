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
}
