<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class reserve extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "reserve";
    protected $fillable = ['price_all_reserve', 'end_reservation', 'start_reservation', 'payment_status'];
    public $timestamps = true;

    public function rooms()
    {
        return $this->belongsToMany(room::class, 'room_reserves');
    }
}
