<?php

namespace App\Validators;

use App\Models\Stable as StableModel;
use App\Exceptions\FieldException;
use App\Exceptions\MsgException;

use App\Validators\User as UserValidator;

class Stable {
    use Base;
    
    const MODEL_NAME = 'stable';
    const SERVICE_MODEL = 'App\Models\Stable';
    const VALID_STATUS = ['A', 'R', 'P'];

    protected $primaryKey = 'stable_id';
    
    protected $requiredFields = [
        'name' => 'Stable name',
        'type' => 'Stable Type',
        'user_id' => 'User',
    ];

}
