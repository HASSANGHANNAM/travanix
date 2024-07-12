<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class rate extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "rate";
    protected $fillable = ['attraction_activity_id', 'resturant_id', 'hotel_id','trip_id', 'rate', 'tourist_id'];
    public $timestamps = true;
    public function comment()
    {
        return $this->belongsTo(Comment::class, 'tourist_id', 'tourist_id');
    }

    public function hotel()
    {
        return $this->belongsTo(hotel::class, 'hotel_id');
    }
}
