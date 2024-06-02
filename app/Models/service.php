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
}
