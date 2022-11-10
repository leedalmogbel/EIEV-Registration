<?php

namespace App\Validators;

use App\Models\Rider as RiderModel;;
use App\Exceptions\FieldException;
use App\Exceptions\MsgException;

class Rider {
    use Base;
    
    const MODEL_NAME = 'rider';
    const SERVICE_MODEL = 'App\Models\Rider';
    const VALID_STATUS = ['A', 'R', 'P'];

    protected $requiredFields = [
        'emiratesId' => 'Emirates ID',
        'discipline' => 'Discipline',
        'feiRegistrationNo' => 'FEI Registration No',
        'feiRegistrationDate' => 'FEI Registration Date',
        'visaType' => 'Visa Category',
        'firstname' => 'Firstname',
        'lastname' => 'Lastname',
        'nationality' => 'Nationality',
        'email' => 'Email',
    ];

    protected $enums = [
        'discipline' => [
            'Discipline',
            RiderModel::DISCIPLINE,
        ],
        'visaType' => [
            'Visa Category',
            RiderModel::VISA_TYPES,
        ]
    ];
}
