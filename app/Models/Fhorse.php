<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fhorse extends Model
{
    use HasFactory;

    protected $fillable = [
        "nfpassportnumber",
        "active",
        "horseid",
        "nfregistration",
        "name",
        "breed",
        "countryorigin",
        "countryoriginshort",
        "dob",
        "gender",
        "color",
        "trainer",
        "owner",
        "stable",
        "feipassport",
        "microchip",
        "division",
        "stableid",
        "divisionid",
        "adminuser",
        "breedid",
        "colourid",
        "genderid",
        "countryoforiginid",
        "trainerid",
        "ownerid",
    ];
}
