<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
     'doc_id',
     'category',
     'patients',
     'experience',
     'bio_data',
     'status',
     'local'
    ];

    protected $casts = [
    'local' => 'array'
    ];

    public function user() {
        return $this->belongsTo(User::class, "doc_id");
    }

    public function appointments(){
        return $this->hasMany(Appointment::class, "doc_id");
    }
}
