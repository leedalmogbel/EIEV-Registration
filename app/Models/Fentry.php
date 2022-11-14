<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'startno',
        'qr'
    ];

    protected $appends = [
        'hgender',
        'color',
        'yob',
        'rgender',
        'qrval'
    ];

    public function getHgenderAttribute()
    {
        $fhorse = Fhorse::where('horseid',$this->horseid)->first();
        if($fhorse){
            return $fhorse->gender;
        }
    }
    public function getRgenderAttribute()
    {
        $frider = Frider::where('riderid',$this->riderid)->first();
        if($frider){
            return $frider->gender;
        }
    }
    public function getColorAttribute()
    {
        $fhorse = Fhorse::where('horseid',$this->horseid)->first();
        if($fhorse){
            return $fhorse->color;
        }
    }
    public function getYobAttribute()
    {
        $fhorse = Fhorse::where('horseid',$this->horseid)->first();
        if($fhorse){
            return $fhorse->dob;
        }
    }
    public function getQrvalAttribute()
    {
        $fprofile = Userprofile::where('userid',$this->userid)->first();
        if($fprofile){
            return $fprofile->stableid.'UP'.Str::padLeft($fprofile->userid,6,'0') ;
        }
    }
}
