<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class attraction_activity extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "attraction_activities";
    protected $fillable = ['name', 'opening_time', 'closing_time', 'description', 'location_id', 'rating_id'];
    public $timestamps = true;
}
