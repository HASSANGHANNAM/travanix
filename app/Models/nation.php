<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class nation extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "nation";
    protected $fillable = ['nation_name_in_arabic', 'nation_name_in_english'];
    public $timestamps = true;
}
