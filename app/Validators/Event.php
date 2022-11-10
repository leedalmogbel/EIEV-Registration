<?php

namespace App\Validators;

use App\Models\Event as EventModel;
use App\Exceptions\FieldException;
use App\Exceptions\MsgException;

class Event {
    use Base;
    
    const MODEL_NAME = 'event';
    const SERVICE_MODEL = 'App\Models\Event';
    const VALID_STATUS = ['A', 'R', 'P'];
    
    protected $requiredFields = [
        'name' => 'Event title',
        'description' => 'Event description',
        'season_id' => 'Season',
        'location' => 'Location',
        'country' => 'Country',
        'start_date' => 'Start date',
        'end_date' => 'End date'
    ];

    protected $modelShouldExist = [
        'season_id' => [
            'Season',
            \App\Services\Season::class,
        ]
    ];
}
