<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reusable extends Model
{
    public static function generateReusableUnique($data,$prefix='',$suffix='')
    {
        return $prefix .'-'.$data
    }
}
