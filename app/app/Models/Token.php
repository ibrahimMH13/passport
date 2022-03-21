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

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function hasExpired(){
        return now()->gte($this->updated_at->addSeconds($this->expires_in));
    }
}
