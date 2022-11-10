<?php

namespace App\Services;

use App\Models\Trainer as TrainerModel;
use App\Validators\Trainer as TrainerValidator;

use App\Exceptions\MsgException;

class Trainer extends TrainerModel {
    use Base;

    const MODEL_VALIDATOR = 'App\Validators\Trainer';
    const PAGINATION_ENTRY = 15;

    protected $searchables = [
        'emiratesId',
        'feiRegistrationNo',
        'firstname',
        'lastname',
        'email',
        'phone',
        'mobile',
    ];

    protected function createPre() {
        // TODO: User user ID from session
        $this->user_id = session()->get('user')->user_id;
    }
}
