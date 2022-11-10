<?php

namespace App\Services;

use App\Models\User as UserModel;

use App\Exceptions\MsgException;

class User extends UserModel {
    use Base;
    
    const MODEL_VALIDATOR = 'App\Validators\User';
    const PAGINATION_ENTRY = 15;
}
