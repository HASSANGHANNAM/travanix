<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class location extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "location";
    protected $fillable = ['address', 'coordinate_x', 'coordinate_y', 'city_id'];
    public $timestamps = true;
    public function hotel()
    {
        return $this->belongsTo(hotel::class);
    }
    public function city()
    {
        return $this->belongsTo(city::class);
    }
}
