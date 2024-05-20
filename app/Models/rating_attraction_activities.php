<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class rating extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "rating_attraction_activities";
    protected $fillable = ['rate', 'attraction_activities_id'];
    public $timestamps = true;
}
