<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class image extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "image";
    protected $fillable = ['attraction_activity_id', 'resturant_id', 'hotel_id', 'path_of_image'];
    public $timestamps = true;
    public function hotels()
    {
        return $this->belongsTo(hotel::class);
    }
    public function resturants()
    {
        return $this->belongsTo(resturant::class);
    }
    public function attraction_activities()
    {
        return $this->belongsTo(attraction_activity::class);
    }
}
