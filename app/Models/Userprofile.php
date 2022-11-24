<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userprofile extends Model
{
    
    use HasFactory;
    protected $fillable = [
        'latestupdate',
        'isactive',
        'email',
        'userid',
        'fname',
        'lname',
        'mobileno',
        'bday',
        'stableid',
    ];

    protected $excludes = [
        'created_at',
        'updated_at'
    ];
}
