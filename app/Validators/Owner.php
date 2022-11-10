<?php

namespace App\Validators;

use App\Models\Owner as OwnerModel;
use App\Exceptions\FieldException;
use App\Exceptions\MsgException;

class Owner {
    use Base;
    
    const MODEL_NAME = 'owner';
    const SERVICE_MODEL = 'App\Models\Owner';
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
            OwnerModel::DISCIPLINE,
        ],
        'visaType' => [
            'Visa Category',
            OwnerModel::VISA_TYPES,
        ]
    ];
}
