<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fstable extends Model
{
    use HasFactory;
    protected $fillable = [
        "lastestupdate",
        "stable_id",
        "name",
        "address",
        "zip",
        "city",
        "country",
        "phone",
        "email",
        "remarks",
        "owner",
        "discipline",
        "category",
        "division",
    ];
}
