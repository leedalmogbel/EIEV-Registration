<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horse extends Model
{
    use HasFactory;

    const BREEDS = [
        'A' => 'Arabian',
        'P' => 'Part Arab',
        'C' => 'Crossbreed',
    ];

    const GENDERS = [
        'M' => 'Male',
        'F' => 'Female',
    ];

    protected $primaryKey = 'horse_id';
    protected $fillable = [
        'name',
        'originalName',
        'countryOfBirth',
        'breed',
        'breeder',
        'birthday',
        'gender',
        'colour',
        'microchipNo',
        'uelnNo',
        'countryOfResidence',
        'sire',
        'dam',
        'sireOfDam',
        'feiPassportNo',
        'feiExpireDate',
        'feiRegNo',
        'owner_id',
        'trainer_id',
        'remarks'
    ];

    public function realBreed() {
        if (!$this->breed || !isset(self::BREEDS[$this->breed])) {
            return '';
        }

        return self::BREEDS[$this->breed];
    }
}
