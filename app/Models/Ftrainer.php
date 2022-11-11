<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ftrainer extends Model
{
    use HasFactory;
    protected $fillable = [
        "photograph",
        "nfx0020license",
        "firstx0020name",
        "familyx0020name",
        "gender",
        "nationality",
        "nationalityshort",
        "dob",
        "stable",
        "feix0020reg",
        "telephone",
        "mobile",
        "email",
        "division",
        "registeredx0020season",
        "active",
        "trainerid",
        "stableid",
        "divisionid",
        "adminuser",
        "nationalityid",
        "address",
        "pobox",
        "city",
        "country",
        "country_short",
        "homeaddress",
        "homecity",
        "homecountry",
        "homecountry_short",
        "weight",
    ];
}
