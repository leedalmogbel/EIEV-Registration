<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fevent extends Model
{
    use HasFactory;
    protected $fillable = [
        "statusid",
        "statusname",
        "typeid",
        "typename",
        "divisionid",
        "divisionname",
        "racecity",
        "racecountry",
        "seasonid",
        "seasonname",
        "raceid",
        "racename",
        "racelocation",
        "raceclub",
        "racefromdate",
        "racetodate",
    ];
}
