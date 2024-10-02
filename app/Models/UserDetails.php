<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio_data',
        'fav',
        'status'
    ];

    protected $casts = [
        'fav' => 'array', 
    ];
   
       public function user() {
           return $this->belongsTo(User::class);
       }
}
