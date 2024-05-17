<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class city extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "city";
    protected $fillable = ['city_name_in_arabic', 'city_name_in_english', 'nation_id'];
    public $timestamps = true;
}
