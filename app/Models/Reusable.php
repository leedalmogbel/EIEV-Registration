<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reusable extends Model
{
    public static function generateReusableUnique($data,$prefix='',$suffix='',$encrypt=false,$type)
    {
        $funique = $data;
        if($encrypt){
            switch ($type) {
                case 'ouuid':
                    $ouuids = Str::of(Reusable::generateOrderuuid())->explode('-');
                    $ouuids = array_slice($ouuids, -2, 2);
                    $funique = implode('-',$ouuids);
                    break;
                case 'mdf':
                    $funique = Reusable::generateMd5($funique);
                    break;
            }
        }
        if ($prefix != ''){
            $funique = $prefix."-".$funique;
        }
        if ($suffix != ''){
            $funique .=  "-".$suffix;
        }
        return $funique;
    }
    public static function generateMd5($data)
    {
        return md5($data);
    }
    public static function verifyMd5($enc,$real)
    {
        return $enc == md5($real);
    }
    public static function generateOrderuuid()
    {
        return (string) Str::orderedUuid();;
    }
}
