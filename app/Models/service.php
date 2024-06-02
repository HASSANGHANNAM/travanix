<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class service extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "service";
    protected $fillable = ['service'];
    public $timestamps = true;
    public function hotels()
    {
        return $this->belongsToMany(hotel::class);
    }
    public function hotel_has_services()
    {
        return $this->belongsToMany(hotel_has_services::class);
    }
}
