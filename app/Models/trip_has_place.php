<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class trip_has_place extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "trip_has_place";
    protected $fillable = ['place_id', 'trip_id', 'type'];
    public $timestamps = true;
}
