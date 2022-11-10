<?php

namespace App\Services;

use App\Models\Horse as HorseModel;
use App\Validators\Horse as HorseValidator;

use App\Exceptions\MsgException;

class Horse extends HorseModel {
    use Base;

    const MODEL_VALIDATOR = 'App\Validators\Horse';
    const PAGINATION_ENTRY = 15;

    protected $searchables = [
        'name',
        'originalName',
        'microchipNo',
        'uelnNo',
        'feiPassportNo',
        'feiRegNo',
    ];
}
