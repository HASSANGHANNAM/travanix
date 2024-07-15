<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class city extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "city";
    protected $fillable = ['city_name', 'nation_id'];
    public $timestamps = true;
    public function nation()
    {
        return $this->belongsTo(nation::class);
    }
}
