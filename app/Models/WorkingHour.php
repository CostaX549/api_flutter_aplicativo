<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingHour extends Model
{
    use HasFactory;
    protected $fillable = [
        'doc_id',
        'day',
        'start',
        'end',
        'interval_start',
        'interval_end',
        // Add any other attributes that should be mass assignable
    ];
}
