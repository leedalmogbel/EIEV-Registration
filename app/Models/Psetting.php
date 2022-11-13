<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Psetting extends Model
{
    protected $fillable = [
        'ipaddress',
        'host'
    ];
}
