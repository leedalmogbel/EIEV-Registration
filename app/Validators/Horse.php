<?php

namespace App\Validators;

use App\Models\Horse as HorseModel;;
use App\Exceptions\FieldException;
use App\Exceptions\MsgException;

class Horse {
    use Base;
    
    const MODEL_NAME = 'Horse';
    const SERVICE_MODEL = 'App\Models\Horse';
    const VALID_STATUS = ['A', 'R', 'P'];

    protected $requiredFields = [
        'name' => 'Horse Name',
        'originalName' => 'Original Name',
        'countryOfBirth' => 'Country of Birth',
        'breed' => 'Breed',
        'birthday' => 'Date of Birth',
        'gender' => 'Gender',
        'microchipNo' => 'Microchip No',
        'uelnNo' => 'UELN No',
        'countryOfResidence' => 'Country of Residence',
        'sire' => 'Sire',
        'dam' => 'Dam',
        'sireOfDam' => 'Sire of Dam',
        'feiPassportNo' => 'FEI Passport No',
        'feiExpireDate' => 'FEI Expiry Date',
        'feiRegNo' => 'FEI Registration No',
        'owner_id' => 'Owner',
        'trainer_id' => 'Trainer',
    ];
    
    protected $modelShouldExist = [
        'owner_id' => [
            'Owner',
            \App\Services\Owner::class,
        ],
        'trainer_id' => [
            'Trainer',
            \App\Services\Trainer::class,
        ]
    ];
    
    protected $enums = [
        'gender' => [
            'Gender',
            HorseModel::GENDERS,
        ],
        'breed' => [
            'Breed',
            HorseModel::BREEDS,
        ]
    ];
}
