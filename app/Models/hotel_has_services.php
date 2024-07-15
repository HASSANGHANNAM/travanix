<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class hotel_has_services extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "hotel_has_services";
    protected $fillable = ['hotel_id', 'service_id'];
    public $timestamps = true;
    public function hotel()
    {
        return $this->belongsTo(hotel::class);
    }
    public function services()
    {
        return $this->hasMany(service::class);
    }
    public function service()
    {
        return $this->belongsTo(service::class);
    }
}
