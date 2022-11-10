<?php

namespace App\Validators;

use App\Models\Trainer as TrainerModel;
use App\Exceptions\FieldException;
use App\Exceptions\MsgException;

class Trainer {
    use Base;
    
    const MODEL_NAME = 'trainer';
    const SERVICE_MODEL = 'App\Models\Trainer';
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
            TrainerModel::DISCIPLINE,
        ],
        'visaType' => [
            'Visa Category',
            TrainerModel::VISA_TYPES,
        ]
    ];
}
