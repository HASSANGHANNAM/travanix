<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class hotel extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "hotel";
    protected $fillable = ['location_id', 'simple_description_about_hotel', 'hotel_name', 'hotel_class', 'phone_number'];
    public $timestamps = true;
    public function images()
    {
        return $this->hasMany(image::class);
    }
    public function location()
    {
        return $this->hasOne(location::class);
    }
    public function hotel_has_services()
    {
        return $this->hasMany(hotel_has_services::class);
    }
}
