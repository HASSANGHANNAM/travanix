<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class tourist extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "tourist";
    protected $fillable = ['wallet', 'user_id'];
    public $timestamps = true;
    public function user()
    {
        return $this->hasOne(user::class);
    }
}
