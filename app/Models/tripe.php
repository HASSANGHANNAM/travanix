<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class tripe extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "tripe";
    protected $fillable = [];
    public $timestamps = true;
}
