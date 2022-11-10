<?php

namespace App\Services;

use App\Models\Season as SeasonModel;
use App\Validators\Season as SeasonValidator;

use App\Exceptions\MsgException;

class Season extends SeasonModel {
    use Base;

    const MODEL_VALIDATOR = 'App\Validators\Season';
    const PAGINATION_ENTRY = 15;

    protected $searchables = ['season'];
}
