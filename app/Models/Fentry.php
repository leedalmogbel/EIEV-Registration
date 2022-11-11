<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fentry extends Model
{
    use HasFactory;
    protected $fillable = [
        "horsename",
        "horsenfid",
        "horsefeiid",
        "ridername",
        "ridernfid",
        "riderfeiid",
        "trainername",
        "trainernfid",
        "trainerfeiid",
        "ownername",
        "stablename",
        "eventcode",
        "classcode",
        "code",
        "userid",
        "riderid",
        "riderstableid",
        "horseid",
        "stableid",
        "trainerid",
        "ownerid",
        "isfetched",
        "status",
        "remarks",
        "datesubmit",
        "islate",
        "ispreridelate",
        "feiremarks",
        "isfeivalid",
        "seasoncode",
        "fee",
        "feestatus",
        "reference",
        "acceptterms",
        "review",
        "paytype",
        "posted",
        "chargeable",
        "parentid",
        "withdrawdate",
        "substidate",
        "docsuploaded",
    ];
}
