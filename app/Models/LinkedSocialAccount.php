<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkedSocialAccount extends Model
{
    use HasFactory;

    protected $fillable = [
       "user_id",
       "provider_id",
       "provider_name"
    ];

    public function user() {
        return $this->belongsTo(User::class, "user_id");
    }
}
