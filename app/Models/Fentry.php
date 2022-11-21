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
        'qrval',
        
        'racecode',
        'racestartcode',
        
        'hgender',
        'color',
        'yob',
        'breed',
        'microchip',
        'horigin',

        'rgender',
        'rcountry',
        'rfname',
        'rlname',


    ];
    // profile includes
    public function getQrvalAttribute()
    {
        $fprofile = Userprofile::where('userid',$this->userid)->first();
        if($fprofile){
            return $fprofile->uniqueid;
        }
    }
    // end profile includes

    // race includes
    public function GetRacestartcodeAttribute()
    {
        $fevent = Fevent::where('raceid','like','%'.strval(intval($this->eventcode ?? 0).'%'))->first();
        if($fevent){
            return $fevent->startcode;
        }
    }
    
    public function GetRacecodeAttribute()
    {
        $frider = Fevent::where('raceid','like',strval(intval($this->eventcode ?? 0)))->first();
        if($frider){
            return Str::contains(Str::lower($frider->typename),'national') ? "1" : "2";
        }
    }
    // end race includes
    // horse includes
    public function getHgenderAttribute()
    {
        $fhorse = Fhorse::where('horseid',$this->horseid)->first();
        if($fhorse){
            return $fhorse->gender;
        }
    }
    public function getMicrochipAttribute()
    {
        $fhorse = Fhorse::where('horseid',$this->horseid)->first();
        if($fhorse){
            return $fhorse->microchip;
        }
    }
    public function getHoriginAttribute()
    {
        $fhorse = Fhorse::where('horseid',$this->horseid)->first();
        if($fhorse){
            return $fhorse->countryoriginshort;
        }
    }
    
    public function getColorAttribute()
    {
        $fhorse = Fhorse::where('horseid',$this->horseid)->first();
        if($fhorse){
            return $fhorse->color;
        }
    }
    public function getBreedAttribute()
    {
        $fhorse = Fhorse::where('horseid',$this->horseid)->first();
        if($fhorse){
            return $fhorse->breed;
        }
    }
    public function getYobAttribute()
    {
        $fhorse = Fhorse::where('horseid',$this->horseid)->first();
        if($fhorse){
            return $fhorse->dob;
        }
    }
    // end horse includes
    // rider includes
    public function GetRcountryAttribute()
    {
        $frider = Frider::where('riderid',$this->riderid)->first();
        if($frider){
            return $frider->nationalityshort;
        }
    }
    public function getRgenderAttribute()
    {
        $frider = Frider::where('riderid',$this->riderid)->first();
        if($frider){
            return $frider->gender;
        }
    }
    public function GetRfnameAttribute()
    {
        $frider = Frider::where('riderid',$this->riderid)->first();
        if($frider){
            return $frider->firstx0020name;
        }
    }
    public function GetRlnameAttribute()
    {
        $frider = Frider::where('riderid',$this->riderid)->first();
        if($frider){
            return $frider->familyx0020name;
        }
    }
    // end rider includes
}
