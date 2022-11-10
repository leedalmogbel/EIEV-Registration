<?php

namespace App\Services;

use App\Models\Event as EventModel;
use App\Validators\Event as EventValidator;

use App\Exceptions\MsgException;

class Event extends EventModel {
    use Base;

    const MODEL_VALIDATOR = 'App\Validators\Event';
    const PAGINATION_ENTRY = 15;

    
    protected $searchables = [
        'name',
    ];
    
    protected function createPre() {
        $this->slug = '';
        $this->user_id = session()->get('user')->user_id; // TODO: User user ID from session
    }
}
