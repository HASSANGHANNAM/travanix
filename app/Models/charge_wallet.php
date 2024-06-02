<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class charge_wallet extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "charge_wallet";
    protected $fillable = ['tourist_id', 'charge_code'];
    public $timestamps = true;
}
