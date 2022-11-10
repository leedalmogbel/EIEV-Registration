<?php

namespace App\Services;

use App\Models\Rider as RiderModel;
use App\Validators\Rider as RiderValidator;

use App\Exceptions\MsgException;

class Rider extends RiderModel {
    use Base;

    const MODEL_VALIDATOR = 'App\Validators\Rider';
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
