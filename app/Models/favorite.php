<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class favorite extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "favorite";
    protected $fillable = ['type', 'favorite_id', 'tourist_id'];
    public $timestamps = true;
}
