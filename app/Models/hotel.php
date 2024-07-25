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
    public function hotel_has_services()
    {
        return $this->hasMany(hotel_has_services::class);
    }
    public function comments()
    {
        return $this->hasMany(comment::class, 'hotel_id');
    }

    public function rates()
    {
        return $this->hasMany(rate::class, 'hotel_id');
    }

    public function commentsWithRates()
    {
        return $this->comments()->join('rates', function ($join) {
            $join->on('comments.tourist_id', '=', 'rates.tourist_id')
                ->where('comments.hotel_id', '=', 'rates.hotel_id');
            dd($join);
        });
    }
    public function location()
    {
        return $this->belongsTo(location::class);
    }
    public function avg_rate()
    {
        return $this->hasOne(avg_rate::class);
    }
    public function favorite()
    {
        return $this->hasOne(avg_rate::class);
    }
    public function rooms()
    {
        return $this->hasMany(room::class);
    }
}
