<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class room extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "room";
    protected $fillable = ['quantity', 'capacity_room', 'price_room', 'hotel_id'];
    public $timestamps = true;

    public function reserves()
    {
        return $this->belongsToMany(reserve::class, 'room_reserves');
    }
    public function hotel()
    {
        return $this->belongsTo(hotel::class);
    }
    public function reservations()
    {
        return $this->belongsToMany(reserve::class, 'room_has_reserve');
    }
    public function getAvailableRoomsCount($start_reservation, $end_reservation)
    {
        $reservedRooms = $this->reserves()
            ->wherePivot('start_reservation', '<=', $end_reservation)
            ->wherePivot('end_reservation', '>=', $start_reservation)
            ->pluck('rooms.id')
            ->toArray();
        return $this->whereNotIn('id', $reservedRooms)
            ->count();
    }
}
