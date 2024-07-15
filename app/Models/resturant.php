<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class resturant extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "resturant";
    protected $fillable = ['location_id', 'type_of_food', 'resturant_name', 'resturant_class', 'closing_time', 'opining_time', 'descreption', 'phone_number'];
    public $timestamps = true;
    public function images()
    {
        return $this->hasMany(image::class);
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
