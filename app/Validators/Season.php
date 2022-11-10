<?php

namespace App\Validators;

use App\Models\Season as SeasonModel;
use App\Exceptions\FieldException;
use App\Exceptions\MsgException;

class Season {
    use Base;
    
    const MODEL_NAME = 'season';
    const SERVICE_MODEL = 'App\Models\Season';
    const VALID_STATUS = ['A', 'R', 'P'];

    protected $requiredFields = [
        'season' => 'Season name',
        'start_date' => 'Start date',
        'end_date' => 'End date',
    ];
}
