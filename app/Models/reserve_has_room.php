<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class reserve_has_room extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "reserve_has_room";
    protected $fillable = ['room_id', 'reserve_id', 'resturant_id', 'number'];
    public $timestamps = true;

    public function rooms()
    {
        return $this->hasMany(room::class);
    }

    public function reserve()
    {
        return $this->belongsTo(reserve::class);
    }
    public function room()
    {
        return $this->belongsTo(room::class);
    }
}
