<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fowner extends Model
{
    use HasFactory;
    protected $fillable = [
        "ownerid",
        "adminuser",
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
        "registeredseasoncode",
        "registeredx0020season",
        "active",
        "riderid",
        "stableid",
        "divisionid",
        "nationalityid",
        "address",
        "pobox",
        "city",
        "country",
        "countryshort",
        "homeaddress",
        "homecity",
        "homecountry",
        "homecountryshort",
    ];
}