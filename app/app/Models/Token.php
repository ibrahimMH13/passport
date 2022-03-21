<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;
    protected $fillable =[
      'token_type',
      'user_id',
      'expires_in',
      'access_token',
      'refresh_token',
    ];
}
