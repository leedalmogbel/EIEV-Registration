<?php

namespace App\Services;

use App\Models\Owner as OwnerModel;
use App\Validators\Owner as OwnerValidator;

use App\Exceptions\MsgException;

class Owner extends OwnerModel {
    use Base;

    const MODEL_VALIDATOR = 'App\Validators\Owner';
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
