<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class tourist_details extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "tourist_details";
    protected $fillable = ['age', 'name', 'tourist_has_trip_id'];
    public $timestamps = true;
    public function tourist_has_trip()
    {
        return $this->belongsTo(tourist_has_trip::class);
    }
}
